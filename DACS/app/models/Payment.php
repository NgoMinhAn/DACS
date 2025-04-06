<?php

class Payment {
    public $id;
    public $booking_id;
    public $user_id;
    public $guide_id;
    public $amount;
    public $payment_method;
    public $status;
    public $description;
    public $transaction_id;
    public $created_at;
    public $updated_at;
    
    // Constructor
    public function __construct($id, $booking_id, $user_id, $guide_id, $amount, $payment_method, $status, $description, $transaction_id = null, $created_at = null, $updated_at = null) {
        $this->id = $id;
        $this->booking_id = $booking_id;
        $this->user_id = $user_id;
        $this->guide_id = $guide_id;
        $this->amount = $amount;
        $this->payment_method = $payment_method;
        $this->status = $status;
        $this->description = $description;
        $this->transaction_id = $transaction_id;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
        $this->updated_at = $updated_at ?? date('Y-m-d H:i:s');
    }
    
    // Create new payment
    public static function create($booking_id, $user_id, $guide_id, $amount, $payment_method, $status, $description, $transaction_id = null) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Check if booking already has a payment
        $stmt = $conn->prepare("SELECT * FROM payments WHERE booking_id = ? AND status = 'completed'");
        $stmt->execute([$booking_id]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception("Booking already has a completed payment");
        }
        
        // Insert new payment
        $stmt = $conn->prepare("
            INSERT INTO payments (
                booking_id, user_id, guide_id, amount, payment_method, 
                status, description, transaction_id, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $now = date('Y-m-d H:i:s');
        $result = $stmt->execute([
            $booking_id, $user_id, $guide_id, $amount, $payment_method,
            $status, $description, $transaction_id, $now, $now
        ]);
        
        if (!$result) {
            throw new Exception("Failed to create payment");
        }
        
        // Get the newly created payment ID
        $id = $conn->lastInsertId();
        
        // Return new payment object
        return new Payment(
            $id, $booking_id, $user_id, $guide_id, $amount, $payment_method,
            $status, $description, $transaction_id, $now, $now
        );
    }
    
    // Find payment by ID
    public static function findById($id) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() === 0) {
            return null;
        }
        
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return new Payment(
            $payment['id'],
            $payment['booking_id'],
            $payment['user_id'],
            $payment['guide_id'],
            $payment['amount'],
            $payment['payment_method'],
            $payment['status'],
            $payment['description'],
            $payment['transaction_id'],
            $payment['created_at'],
            $payment['updated_at']
        );
    }
    
    // Find payment by booking ID
    public static function findByBookingId($booking_id) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("SELECT * FROM payments WHERE booking_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$booking_id]);
        
        if ($stmt->rowCount() === 0) {
            return null;
        }
        
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return new Payment(
            $payment['id'],
            $payment['booking_id'],
            $payment['user_id'],
            $payment['guide_id'],
            $payment['amount'],
            $payment['payment_method'],
            $payment['status'],
            $payment['description'],
            $payment['transaction_id'],
            $payment['created_at'],
            $payment['updated_at']
        );
    }
    
    // Get all payments for a user
    public static function getByUserId($user_id) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("
            SELECT p.*, b.date, b.start_time, u.name as guide_name 
            FROM payments p 
            JOIN bookings b ON p.booking_id = b.id 
            JOIN tour_guides tg ON p.guide_id = tg.id 
            JOIN users u ON tg.user_id = u.id 
            WHERE p.user_id = ? 
            ORDER BY p.created_at DESC
        ");
        
        $stmt->execute([$user_id]);
        
        $payments = [];
        while ($payment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $payments[] = new Payment(
                $payment['id'],
                $payment['booking_id'],
                $payment['user_id'],
                $payment['guide_id'],
                $payment['amount'],
                $payment['payment_method'],
                $payment['status'],
                $payment['description'],
                $payment['transaction_id'],
                $payment['created_at'],
                $payment['updated_at']
            );
        }
        
        return $payments;
    }
    
    // Get all payments for a guide
    public static function getByGuideId($guide_id) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("
            SELECT p.*, b.date, b.start_time, u.name as user_name 
            FROM payments p 
            JOIN bookings b ON p.booking_id = b.id 
            JOIN users u ON p.user_id = u.id 
            WHERE p.guide_id = ? 
            ORDER BY p.created_at DESC
        ");
        
        $stmt->execute([$guide_id]);
        
        $payments = [];
        while ($payment = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $payments[] = new Payment(
                $payment['id'],
                $payment['booking_id'],
                $payment['user_id'],
                $payment['guide_id'],
                $payment['amount'],
                $payment['payment_method'],
                $payment['status'],
                $payment['description'],
                $payment['transaction_id'],
                $payment['created_at'],
                $payment['updated_at']
            );
        }
        
        return $payments;
    }
    
    // Update payment status
    public function updateStatus($status) {
        // Validate status
        $valid_statuses = ['pending', 'processing', 'completed', 'failed', 'refunded'];
        if (!in_array($status, $valid_statuses)) {
            throw new Exception("Invalid payment status");
        }
        
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Update payment status
        $stmt = $conn->prepare("UPDATE payments SET status = ?, updated_at = ? WHERE id = ?");
        $now = date('Y-m-d H:i:s');
        $result = $stmt->execute([$status, $now, $this->id]);
        
        if (!$result) {
            throw new Exception("Failed to update payment status");
        }
        
        // Update object property
        $this->status = $status;
        $this->updated_at = $now;
        
        // Update booking payment status if payment is completed
        if ($status === 'completed') {
            $booking = Booking::findById($this->booking_id);
            $booking->updatePaymentStatus('paid');
            $booking->updateStatus('confirmed');
        }
        
        // Update booking payment status if payment is refunded
        if ($status === 'refunded') {
            $booking = Booking::findById($this->booking_id);
            $booking->updatePaymentStatus('refunded');
            $booking->updateStatus('cancelled');
        }
        
        return true;
    }
    
    // Process refund
    public function refund() {
        // Check if payment can be refunded
        if ($this->status !== 'completed') {
            throw new Exception("Only completed payments can be refunded");
        }
        
        // In a real application, you would integrate with a payment gateway's refund API here
        
        // Update payment status
        return $this->updateStatus('refunded');
    }
    
    // Get user object associated with this payment
    public function getUser() {
        return User::findById($this->user_id);
    }
    
    // Get guide object associated with this payment
    public function getGuide() {
        return TourGuide::findById($this->guide_id);
    }
    
    // Get booking object associated with this payment
    public function getBooking() {
        return Booking::findById($this->booking_id);
    }
    
    // Calculate guide's earnings from this payment
    public function getGuideEarnings() {
        // For example, guide gets 80% of the payment amount
        $commission_rate = 0.8;
        return $this->amount * $commission_rate;
    }
    
    // Calculate platform fee from this payment
    public function getPlatformFee() {
        // For example, platform gets 20% of the payment amount
        $fee_rate = 0.2;
        return $this->amount * $fee_rate;
    }
} 