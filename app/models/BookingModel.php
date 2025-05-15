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
     * Get all bookings for a user
     * 
     * @param int $userId The user ID
     * @return array The bookings
     */
    public function getUserBookings($userId) {
        $this->db->query('
            SELECT b.*, u.name as guide_name, u.profile_image as guide_image
            FROM bookings b
            JOIN guide_profiles g ON b.guide_id = g.id
            JOIN users u ON g.user_id = u.id
            WHERE b.user_id = :user_id
            ORDER BY b.booking_date DESC, b.start_time DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }

    public function getBookingById($bookingId, $userId) {
    $this->db->query('
        SELECT b.*, u.name as guide_name, u.profile_image as guide_image
        FROM bookings b
        JOIN guide_profiles g ON b.guide_id = g.id
        JOIN users u ON g.user_id = u.id
        WHERE b.id = :booking_id AND b.user_id = :user_id
        LIMIT 1
    ');
    $this->db->bind(':booking_id', $bookingId);
    $this->db->bind(':user_id', $userId);
    return $this->db->single();
    }
} 