<?php

class PaymentController {
    
    // Show payment form for a booking
    public function create($booking_id) {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to make a payment'];
            header('Location: /login');
            exit;
        }
        
        // Get booking details
        $booking = Booking::findById($booking_id);
        
        if (!$booking) {
            $_SESSION['errors'] = ['Booking not found'];
            header('Location: /bookings');
            exit;
        }
        
        // Check if user is authorized to pay for this booking
        if ($booking->user_id !== $_SESSION['user_id']) {
            $_SESSION['errors'] = ['You can only pay for your own bookings'];
            header('Location: /bookings');
            exit;
        }
        
        // Check if booking status is approved (ready for payment)
        if ($booking->status !== 'approved') {
            $_SESSION['errors'] = ['Payment can only be made for approved bookings'];
            header('Location: /bookings');
            exit;
         }
        
        // Check if payment has already been made
        if ($booking->payment_status === 'paid') {
            $_SESSION['errors'] = ['Payment has already been made for this booking'];
            header('Location: /bookings');
            exit;
        }
        
        // Get guide details
        $guide = TourGuide::findById($booking->guide_id);
        
        require_once 'app/views/payments/create.php';
    }
    
    // Process payment
    public function store() {
        // Validate input
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bookings');
            exit;
        }
        
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to make a payment'];
            header('Location: /login');
            exit;
        }
        
        // Extract form data
        $booking_id = $_POST['booking_id'] ?? '';
        $payment_method = $_POST['payment_method'] ?? '';
        $card_number = $_POST['card_number'] ?? '';
        $card_expiry = $_POST['card_expiry'] ?? '';
        $card_cvv = $_POST['card_cvv'] ?? '';
        $cardholder_name = $_POST['cardholder_name'] ?? '';
        
        // Validate data
        $errors = [];
        if (empty($booking_id)) $errors[] = 'Booking ID is required';
        if (empty($payment_method)) $errors[] = 'Payment method is required';
        
        // If credit card payment, validate card details
        if ($payment_method === 'credit_card') {
            if (empty($card_number)) $errors[] = 'Card number is required';
            if (empty($card_expiry)) $errors[] = 'Card expiry date is required';
            if (empty($card_cvv)) $errors[] = 'Card CVV is required';
            if (empty($cardholder_name)) $errors[] = 'Cardholder name is required';
            
            // Basic card number validation
            if (!empty($card_number) && (!is_numeric($card_number) || strlen($card_number) < 13)) {
                $errors[] = 'Invalid card number';
            }
            
            // Basic expiry date validation (MM/YY format)
            if (!empty($card_expiry) && !preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $card_expiry)) {
                $errors[] = 'Invalid expiry date (use MM/YY format)';
            }
            
            // Basic CVV validation
            if (!empty($card_cvv) && (!is_numeric($card_cvv) || strlen($card_cvv) < 3 || strlen($card_cvv) > 4)) {
                $errors[] = 'Invalid CVV';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'payment_method' => $payment_method,
                'cardholder_name' => $cardholder_name
            ];
            header("Location: /payments/create/$booking_id");
            exit;
        }
        
        // Get booking details
        $booking = Booking::findById($booking_id);
        
        if (!$booking) {
            $_SESSION['errors'] = ['Booking not found'];
            header('Location: /bookings');
            exit;
        }
        
        // Check if user is authorized to pay for this booking
        if ($booking->user_id !== $_SESSION['user_id']) {
            $_SESSION['errors'] = ['You can only pay for your own bookings'];
            header('Location: /bookings');
            exit;
        }
        
        // Check if booking status is approved (ready for payment)
        if ($booking->status !== 'approved') {
            $_SESSION['errors'] = ['Payment can only be made for approved bookings'];
            header('Location: /bookings');
            exit;
        }
        
        // Check if payment has already been made
        if ($booking->payment_status === 'paid') {
            $_SESSION['errors'] = ['Payment has already been made for this booking'];
            header('Location: /bookings');
            exit;
        }
        
        // Process payment (this would typically integrate with a payment gateway)
        // In a real application, you would use a payment gateway API here
        // For demo purposes, we'll just simulate a successful payment
        
        // Record payment in database
        $payment = Payment::create(
            $booking_id,
            $_SESSION['user_id'],
            $booking->guide_id,
            $booking->total_price,
            $payment_method,
            'completed',
            'Payment for tour booking #' . $booking_id
        );
        
        // Update booking payment status
        $booking->updatePaymentStatus('paid');
        
        // Update booking status to 'confirmed'
        $booking->updateStatus('confirmed');
        
        $_SESSION['success'] = 'Payment processed successfully';
        header('Location: /bookings');
        exit;
    }
    
    // View payment receipt
    public function show($payment_id) {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to view payment details'];
            header('Location: /login');
            exit;
        }
        
        // Get payment details
        $payment = Payment::findById($payment_id);
        
        if (!$payment) {
            $_SESSION['errors'] = ['Payment not found'];
            header('Location: /bookings');
            exit;
        }
        
        // Check if user is authorized to view this payment
        if ($payment->user_id !== $_SESSION['user_id'] && $payment->guide_id !== $_SESSION['user_id']) {
            $_SESSION['errors'] = ['You are not authorized to view this payment'];
            header('Location: /bookings');
            exit;
        }
        
        // Get booking details
        $booking = Booking::findById($payment->booking_id);
        
        require_once 'app/views/payments/show.php';
    }
    
    // View all payments (for the logged-in user)
    public function index() {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to view payments'];
            header('Location: /login');
            exit;
        }
        
        // Get user's payments
        $payments = Payment::getByUserId($_SESSION['user_id']);
        
        require_once 'app/views/payments/index.php';
    }
    
    // Download payment receipt
    public function downloadReceipt($payment_id) {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to download receipt'];
            header('Location: /login');
            exit;
        }
        
        // Get payment details
        $payment = Payment::findById($payment_id);
        
        if (!$payment) {
            $_SESSION['errors'] = ['Payment not found'];
            header('Location: /bookings');
            exit;
        }
        
        // Check if user is authorized to download this receipt
        if ($payment->user_id !== $_SESSION['user_id'] && $payment->guide_id !== $_SESSION['user_id']) {
            $_SESSION['errors'] = ['You are not authorized to download this receipt'];
            header('Location: /bookings');
            exit;
        }
        
        // Get booking and user details
        $booking = Booking::findById($payment->booking_id);
        $user = User::findById($payment->user_id);
        $guide = TourGuide::findById($payment->guide_id);
        
        // Generate receipt PDF
        // $pdf = new FPDF();
        // $pdf->AddPage();
        // $pdf->SetFont('Arial', 'B', 16);
        // $pdf->Cell(40, 10, 'Payment Receipt');
        // ... add more PDF content here
        // $pdf->Output('D', 'receipt_' . $payment_id . '.pdf');
        
        // For now, just redirect back to the payment page
        $_SESSION['error'] = 'Receipt download feature is not yet implemented';
        header("Location: /payments/$payment_id");
        exit;
    }
    
    // Guide: View earnings and payment history
    public function guideEarnings() {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to view earnings'];
            header('Location: /login');
            exit;
        }
        
        // Check if user is a guide
        $guide = TourGuide::findByUserId($_SESSION['user_id']);
        
        if (!$guide) {
            $_SESSION['errors'] = ['You must be a registered guide to view earnings'];
            header('Location: /dashboard');
            exit;
        }
        
        // Get guide's payments
        $payments = Payment::getByGuideId($guide->id);
        
        // Calculate total earnings
        $total_earnings = 0;
        foreach ($payments as $payment) {
            if ($payment->status === 'completed') {
                $total_earnings += $payment->amount;
            }
        }
        
        require_once 'app/views/payments/guide_earnings.php';
    }
} 