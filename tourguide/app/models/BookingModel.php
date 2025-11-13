<?php
/**
 * Booking Model
 * Handles all booking-related database operations
 */
class BookingModel {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Get all bookings for a user with payment information
     * 
     * @param int $userId The user ID
     * @param int $limit Optional limit for pagination
     * @param int $offset Optional offset for pagination
     * @return array The bookings with payment details
     */
    public function getUserBookings($userId, $limit = null, $offset = null) {
        $sql = '
            SELECT 
                b.*, 
                u.name as guide_name, 
                u.profile_image as guide_image,
                v.vnp_TransactionNo,
                v.vnp_TransactionStatus,
                v.vnp_BankCode,
                v.vnp_BankTranNo,
                v.vnp_CardType,
                v.payment_status as transaction_payment_status,
                v.payment_method as transaction_payment_method,
                v.created_at as transaction_created_at,
                v.vnp_PayDate as transaction_pay_date
            FROM bookings b
            JOIN guide_profiles g ON b.guide_id = g.id
            JOIN users u ON g.user_id = u.id
            LEFT JOIN vnpay_transactions v ON v.booking_id = b.id
            WHERE b.user_id = :user_id
            ORDER BY b.created_at DESC, b.booking_date DESC
        ';
        
        // Add limit and offset for pagination if provided
        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
            if ($offset !== null) {
                $sql .= ' OFFSET :offset';
            }
        }
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        
        if ($limit !== null) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            }
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get total count of bookings for a user
     * 
     * @param int $userId The user ID
     * @return int Total number of bookings
     */
    public function getUserBookingsCount($userId) {
        $this->db->query('
            SELECT COUNT(*) as total
            FROM bookings b
            WHERE b.user_id = :user_id
        ');
        
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        
        return $result ? (int)$result->total : 0;
    }

    public function getBookingById($bookingId, $userId) {
        $this->db->query('
            SELECT 
                b.*, 
                u.name as guide_name, 
                u.profile_image as guide_image,
                u.email as guide_email,
                u.phone as guide_phone,
                v.vnp_TxnRef,
                v.vnp_TransactionNo,
                v.vnp_Amount,
                v.vnp_OrderInfo,
                v.vnp_ResponseCode,
                v.vnp_TransactionStatus,
                v.vnp_BankCode,
                v.vnp_BankTranNo,
                v.vnp_CardType,
                v.vnp_PayDate as transaction_pay_date,
                v.payment_status as transaction_payment_status,
                v.payment_method as transaction_payment_method,
                v.ip_address,
                v.created_at as transaction_created_at
            FROM bookings b
            JOIN guide_profiles g ON b.guide_id = g.id
            JOIN users u ON g.user_id = u.id
            LEFT JOIN vnpay_transactions v ON v.booking_id = b.id
            WHERE b.id = :booking_id AND b.user_id = :user_id
            LIMIT 1
        ');
        $this->db->bind(':booking_id', $bookingId);
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }
} 