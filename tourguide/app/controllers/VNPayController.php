<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  
class VNPayController {
    private $vnp_TmnCode = "OSNSAXQM"; //Mã định danh merchant kết nối (Terminal Id)
    private $vnp_HashSecret = "LROBP8XF6RNAXDYEJHG6RZ0GA4F1GON9"; //Secret key
    private $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    private $vnp_Returnurl = URL_ROOT . "/vnpay/return";
    private $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
    private $apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
    private $guideModel;

    public function __construct() {
        $this->guideModel = new GuideModel();
    }

    public function createPayment() {
        // Get parameters from GET request
        $amount = $_GET['amount'] ?? 0;
        $orderInfo = $_GET['orderInfo'] ?? 'Tour Booking';
        $orderId = $_GET['orderId'] ?? uniqid();
        $guide_id = $_GET['guide_id'] ?? '';

        $vnp_TxnRef = $orderId; //Mã đơn hàng
        $vnp_OrderInfo = $orderInfo;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // Số tiền * 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_CreateDate = date('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $this->vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Redirect to VNPay payment page
        header('Location: ' . $vnp_Url);
        exit();
    }

    public function return() {
        // Handle payment return
        $vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? '';
        $vnp_TxnRef = $_GET['vnp_TxnRef'] ?? '';
        $vnp_Amount = $_GET['vnp_Amount'] ?? 0;
        $vnp_OrderInfo = $_GET['vnp_OrderInfo'] ?? '';
        $vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';
        $vnp_TransactionNo = $_GET['vnp_TransactionNo'] ?? '';
        $guide_id = $_GET['guide_id'] ?? '';

        // Verify the payment result
        if ($vnp_ResponseCode == '00') {
            // Payment successful
            if (isset($_SESSION['pending_booking'])) {
                $booking_data = $_SESSION['pending_booking'];
                $user_id = $_SESSION['user_id'] ?? null;

                // Calculate end_time and total_price
                if ($booking_data['booking_type'] === 'hourly') {
                    $end_time = date('H:i:s', strtotime($booking_data['start_time']) + ($booking_data['hours'] * 3600));
                    $total_hours = $booking_data['hours'];
                } else {
                    $start_time = '09:00:00';
                    $end_time = '17:00:00';
                    $total_hours = 8;
                }

                // Create booking with paid status
                $db = new Database();
                $db->query('INSERT INTO bookings (guide_id, user_id, booking_date, start_time, end_time, total_hours, total_price, status, payment_status, transaction_id, special_requests, number_of_people, meeting_location) VALUES (:guide_id, :user_id, :booking_date, :start_time, :end_time, :total_hours, :total_price, :status, :payment_status, :transaction_id, :special_requests, :number_of_people, :meeting_location)');
                
                $db->bind(':guide_id', $booking_data['guide_id']);
                $db->bind(':user_id', $user_id);
                $db->bind(':booking_date', $booking_data['booking_date']);
                $db->bind(':start_time', $booking_data['start_time']);
                $db->bind(':end_time', $end_time);
                $db->bind(':total_hours', $total_hours);
                $db->bind(':total_price', $booking_data['total_amount']);
                $db->bind(':status', 'confirmed');
                $db->bind(':payment_status', 'paid');
                $db->bind(':transaction_id', $vnp_TransactionNo);
                $db->bind(':special_requests', $booking_data['special_requests']);
                $db->bind(':number_of_people', $booking_data['number_of_people']);
                $db->bind(':meeting_location', $booking_data['meeting_location']);

                if ($db->execute()) {
                    // Clear pending booking from session
                    unset($_SESSION['pending_booking']);
                    flash('success_message', 'Payment successful! Your booking has been confirmed.', 'alert alert-success');
                } else {
                    flash('error_message', 'Payment successful but failed to create booking. Please contact support.', 'alert alert-warning');
                }
            } else {
                flash('error_message', 'Payment successful but booking information is missing. Please contact support.', 'alert alert-warning');
            }
        } else {
            // Payment failed
            flash('error_message', 'Payment failed. Please try again.', 'alert alert-danger');
        }

        // Redirect back to the booking page
        redirect('tourGuide/profile/' . $guide_id);
    }
}
