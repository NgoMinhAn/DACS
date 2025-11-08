<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  
class VNPayController {
    private $vnp_TmnCode = "4BA3R3VC"; //Mã định danh merchant kết nối (Terminal Id)
    private $vnp_HashSecret = "3UMD6M37HCXKL91M30XZKXJPSKJYQC0X"; //Secret key
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
        // Handle payment return - Get all VNPay parameters
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);

        // Extract VNPay parameters
        $vnp_ResponseCode = $inputData['vnp_ResponseCode'] ?? '';
        $vnp_TxnRef = $inputData['vnp_TxnRef'] ?? '';
        $vnp_Amount = isset($inputData['vnp_Amount']) ? $inputData['vnp_Amount'] / 100 : 0; // Convert from VND (x100) to actual amount
        $vnp_OrderInfo = $inputData['vnp_OrderInfo'] ?? '';
        $vnp_TransactionNo = $inputData['vnp_TransactionNo'] ?? '';
        $vnp_TransactionStatus = $inputData['vnp_TransactionStatus'] ?? '';
        $vnp_BankCode = $inputData['vnp_BankCode'] ?? '';
        $vnp_BankTranNo = $inputData['vnp_BankTranNo'] ?? '';
        $vnp_CardType = $inputData['vnp_CardType'] ?? '';
        $vnp_PayDate = isset($inputData['vnp_PayDate']) ? $this->formatPayDate($inputData['vnp_PayDate']) : null;
        $guide_id = $_GET['guide_id'] ?? '';
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

        // Verify secure hash
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        // Verify hash
        if ($secureHash !== $vnp_SecureHash) {
            flash('error_message', 'Invalid payment signature. Please contact support.', 'alert alert-danger');
            redirect('tourGuide/profile/' . $guide_id);
            return;
        }

        // Determine payment status
        $payment_status = 'pending';
        if ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00') {
            $payment_status = 'success';
        } else {
            $payment_status = 'failed';
        }

        // Initialize database
        $db = new Database();

        // Check if transaction already exists to avoid duplicate processing
        $db->query('SELECT id, booking_id FROM vnpay_transactions WHERE vnp_TxnRef = :vnp_TxnRef');
        $db->bind(':vnp_TxnRef', $vnp_TxnRef);
        $existingTransaction = $db->single();

        $booking_id = null;

        if ($payment_status == 'success') {
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

                // Start transaction to ensure data consistency
                $db->beginTransaction();

                try {
                    // Check if booking already exists for this transaction
                    if ($existingTransaction && $existingTransaction->booking_id) {
                        $booking_id = $existingTransaction->booking_id;
                    } else {
                        // Create booking with paid status
                        $insertBookingQuery = 'INSERT INTO bookings (guide_id, user_id, booking_date, start_time, end_time, total_hours, total_price, status, payment_status, transaction_id, special_requests, number_of_people, meeting_location, payment_date, payment_method, bank_code) VALUES (:guide_id, :user_id, :booking_date, :start_time, :end_time, :total_hours, :total_price, :status, :payment_status, :transaction_id, :special_requests, :number_of_people, :meeting_location, :payment_date, :payment_method, :bank_code)';
                        
                        $db->query($insertBookingQuery);
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
                        $db->bind(':special_requests', $booking_data['special_requests'] ?? null);
                        $db->bind(':number_of_people', $booking_data['number_of_people'] ?? 1);
                        $db->bind(':meeting_location', $booking_data['meeting_location'] ?? null);
                        $db->bind(':payment_date', $vnp_PayDate);
                        $db->bind(':payment_method', 'VNPay');
                        $db->bind(':bank_code', $vnp_BankCode);

                        if ($db->execute()) {
                            $booking_id = $db->lastInsertId();
                        } else {
                            throw new Exception('Failed to create booking');
                        }
                    }

                    // Save or update transaction in vnpay_transactions table
                    if ($existingTransaction) {
                        // Update existing transaction
                        $db->query('UPDATE vnpay_transactions SET 
                            booking_id = :booking_id,
                            vnp_TransactionNo = :vnp_TransactionNo,
                            vnp_Amount = :vnp_Amount,
                            vnp_OrderInfo = :vnp_OrderInfo,
                            vnp_ResponseCode = :vnp_ResponseCode,
                            vnp_TransactionStatus = :vnp_TransactionStatus,
                            vnp_BankCode = :vnp_BankCode,
                            vnp_BankTranNo = :vnp_BankTranNo,
                            vnp_CardType = :vnp_CardType,
                            vnp_PayDate = :vnp_PayDate,
                            payment_status = :payment_status,
                            payment_method = :payment_method,
                            ip_address = :ip_address
                            WHERE id = :id');
                        
                        $db->bind(':id', $existingTransaction->id);
                        $db->bind(':booking_id', $booking_id);
                        $db->bind(':vnp_TransactionNo', $vnp_TransactionNo);
                        $db->bind(':vnp_Amount', $vnp_Amount);
                        $db->bind(':vnp_OrderInfo', $vnp_OrderInfo);
                        $db->bind(':vnp_ResponseCode', $vnp_ResponseCode);
                        $db->bind(':vnp_TransactionStatus', $vnp_TransactionStatus);
                        $db->bind(':vnp_BankCode', $vnp_BankCode);
                        $db->bind(':vnp_BankTranNo', $vnp_BankTranNo);
                        $db->bind(':vnp_CardType', $vnp_CardType);
                        $db->bind(':vnp_PayDate', $vnp_PayDate);
                        $db->bind(':payment_status', $payment_status);
                        $db->bind(':payment_method', 'VNPay');
                        $db->bind(':ip_address', $ip_address);
                    } else {
                        // Insert new transaction
                        $db->query('INSERT INTO vnpay_transactions (
                            booking_id, vnp_TxnRef, vnp_TransactionNo, vnp_Amount, vnp_OrderInfo,
                            vnp_ResponseCode, vnp_TransactionStatus, vnp_BankCode, vnp_BankTranNo,
                            vnp_CardType, vnp_PayDate, payment_status, payment_method, ip_address
                        ) VALUES (
                            :booking_id, :vnp_TxnRef, :vnp_TransactionNo, :vnp_Amount, :vnp_OrderInfo,
                            :vnp_ResponseCode, :vnp_TransactionStatus, :vnp_BankCode, :vnp_BankTranNo,
                            :vnp_CardType, :vnp_PayDate, :payment_status, :payment_method, :ip_address
                        )');
                        
                        $db->bind(':booking_id', $booking_id);
                        $db->bind(':vnp_TxnRef', $vnp_TxnRef);
                        $db->bind(':vnp_TransactionNo', $vnp_TransactionNo);
                        $db->bind(':vnp_Amount', $vnp_Amount);
                        $db->bind(':vnp_OrderInfo', $vnp_OrderInfo);
                        $db->bind(':vnp_ResponseCode', $vnp_ResponseCode);
                        $db->bind(':vnp_TransactionStatus', $vnp_TransactionStatus);
                        $db->bind(':vnp_BankCode', $vnp_BankCode);
                        $db->bind(':vnp_BankTranNo', $vnp_BankTranNo);
                        $db->bind(':vnp_CardType', $vnp_CardType);
                        $db->bind(':vnp_PayDate', $vnp_PayDate);
                        $db->bind(':payment_status', $payment_status);
                        $db->bind(':payment_method', 'VNPay');
                        $db->bind(':ip_address', $ip_address);
                    }

                    if (!$db->execute()) {
                        throw new Exception('Failed to save transaction');
                    }

                    // Update booking with payment details if booking exists
                    if ($booking_id) {
                        $db->query('UPDATE bookings SET 
                            payment_date = :payment_date,
                            payment_method = :payment_method,
                            bank_code = :bank_code
                            WHERE id = :booking_id');
                        
                        $db->bind(':booking_id', $booking_id);
                        $db->bind(':payment_date', $vnp_PayDate);
                        $db->bind(':payment_method', 'VNPay');
                        $db->bind(':bank_code', $vnp_BankCode);
                        $db->execute();
                    }

                    // Commit transaction
                    $db->commit();

                    // Clear pending booking from session
                    unset($_SESSION['pending_booking']);
                    flash('success_message', 'Payment successful! Your booking has been confirmed.', 'alert alert-success');
                } catch (Exception $e) {
                    // Rollback on error
                    $db->rollBack();
                    flash('error_message', 'Payment successful but failed to save transaction. Please contact support.', 'alert alert-warning');
                }
            } else {
                // No pending booking in session, but still save the transaction
                if (!$existingTransaction) {
                    $db->query('INSERT INTO vnpay_transactions (
                        booking_id, vnp_TxnRef, vnp_TransactionNo, vnp_Amount, vnp_OrderInfo,
                        vnp_ResponseCode, vnp_TransactionStatus, vnp_BankCode, vnp_BankTranNo,
                        vnp_CardType, vnp_PayDate, payment_status, payment_method, ip_address
                    ) VALUES (
                        :booking_id, :vnp_TxnRef, :vnp_TransactionNo, :vnp_Amount, :vnp_OrderInfo,
                        :vnp_ResponseCode, :vnp_TransactionStatus, :vnp_BankCode, :vnp_BankTranNo,
                        :vnp_CardType, :vnp_PayDate, :payment_status, :payment_method, :ip_address
                    )');
                    
                    $db->bind(':booking_id', null);
                    $db->bind(':vnp_TxnRef', $vnp_TxnRef);
                    $db->bind(':vnp_TransactionNo', $vnp_TransactionNo);
                    $db->bind(':vnp_Amount', $vnp_Amount);
                    $db->bind(':vnp_OrderInfo', $vnp_OrderInfo);
                    $db->bind(':vnp_ResponseCode', $vnp_ResponseCode);
                    $db->bind(':vnp_TransactionStatus', $vnp_TransactionStatus);
                    $db->bind(':vnp_BankCode', $vnp_BankCode);
                    $db->bind(':vnp_BankTranNo', $vnp_BankTranNo);
                    $db->bind(':vnp_CardType', $vnp_CardType);
                    $db->bind(':vnp_PayDate', $vnp_PayDate);
                    $db->bind(':payment_status', $payment_status);
                    $db->bind(':payment_method', 'VNPay');
                    $db->bind(':ip_address', $ip_address);
                    $db->execute();
                }
                
                flash('error_message', 'Payment successful but booking information is missing. Please contact support.', 'alert alert-warning');
            }
        } else {
            // Payment failed - still save transaction for record keeping
            if (!$existingTransaction) {
                $db->query('INSERT INTO vnpay_transactions (
                    booking_id, vnp_TxnRef, vnp_TransactionNo, vnp_Amount, vnp_OrderInfo,
                    vnp_ResponseCode, vnp_TransactionStatus, vnp_BankCode, vnp_BankTranNo,
                    vnp_CardType, vnp_PayDate, payment_status, payment_method, ip_address
                ) VALUES (
                    :booking_id, :vnp_TxnRef, :vnp_TransactionNo, :vnp_Amount, :vnp_OrderInfo,
                    :vnp_ResponseCode, :vnp_TransactionStatus, :vnp_BankCode, :vnp_BankTranNo,
                    :vnp_CardType, :vnp_PayDate, :payment_status, :payment_method, :ip_address
                )');
                
                $db->bind(':booking_id', null);
                $db->bind(':vnp_TxnRef', $vnp_TxnRef);
                $db->bind(':vnp_TransactionNo', $vnp_TransactionNo ?? null);
                $db->bind(':vnp_Amount', $vnp_Amount);
                $db->bind(':vnp_OrderInfo', $vnp_OrderInfo);
                $db->bind(':vnp_ResponseCode', $vnp_ResponseCode);
                $db->bind(':vnp_TransactionStatus', $vnp_TransactionStatus);
                $db->bind(':vnp_BankCode', $vnp_BankCode);
                $db->bind(':vnp_BankTranNo', $vnp_BankTranNo);
                $db->bind(':vnp_CardType', $vnp_CardType);
                $db->bind(':vnp_PayDate', $vnp_PayDate);
                $db->bind(':payment_status', $payment_status);
                $db->bind(':payment_method', 'VNPay');
                $db->bind(':ip_address', $ip_address);
                $db->execute();
            }
            
            flash('error_message', 'Payment failed. Please try again.', 'alert alert-danger');
        }

        // Redirect back to the booking page
        if ($guide_id) {
            redirect('tourGuide/profile/' . $guide_id);
        } else {
            redirect('');
        }
    }

    /**
     * Format VNPay PayDate to MySQL datetime format
     * VNPay format: YYYYMMDDHHmmss
     * MySQL format: YYYY-MM-DD HH:mm:ss
     */
    private function formatPayDate($payDate) {
        if (empty($payDate) || strlen($payDate) != 14) {
            return date('Y-m-d H:i:s');
        }
        
        $year = substr($payDate, 0, 4);
        $month = substr($payDate, 4, 2);
        $day = substr($payDate, 6, 2);
        $hour = substr($payDate, 8, 2);
        $minute = substr($payDate, 10, 2);
        $second = substr($payDate, 12, 2);
        
        return "$year-$month-$day $hour:$minute:$second";
    }
}
