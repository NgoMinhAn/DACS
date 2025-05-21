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
    private $vnp_Returnurl = "http://localhost/DACS/vnpay/return";
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
            flash('success_message', 'Payment successful! Your booking has been confirmed.', 'alert alert-success');
        } else {
            // Payment failed
            flash('error_message', 'Payment failed. Please try again.', 'alert alert-danger');
        }

        // Redirect back to the booking page
        redirect('tourGuide/profile/' . $guide_id);
    }
}
