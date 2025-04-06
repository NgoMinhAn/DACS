<?php
/**
 * Booking Model
 * Handles all booking-related database operations
 */
class Booking {
    private $db;
    
    /**
     * Constructor - initialize database connection
     */
    public function __construct() {
        $this->db = getDbConnection();
    }
    
    /**
     * Find booking by ID
     * 
     * @param int $id Booking ID
     * @return object|false Booking object or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   u.name as user_name, u.email as user_email,
                   g.speciality as guide_speciality,
                   gu.name as guide_name
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN guides g ON b.guide_id = g.id
            JOIN users gu ON g.user_id = gu.id
            WHERE b.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get bookings for a user
     * 
     * @param int $userId User ID
     * @param int $page Page number
     * @param int $perPage Records per page
     * @return array Array of booking objects
     */
    public function getByUserId($userId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   gu.name as guide_name,
                   g.speciality as guide_speciality,
                   g.hourly_rate
            FROM bookings b
            JOIN guides g ON b.guide_id = g.id
            JOIN users gu ON g.user_id = gu.id
            WHERE b.user_id = :user_id
            ORDER BY b.date DESC
            LIMIT :offset, :limit
        ");
        
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count bookings for a user
     * 
     * @param int $userId User ID
     * @return int Number of bookings
     */
    public function countByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM bookings WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get bookings for a guide
     * 
     * @param int $guideId Guide ID
     * @param int $page Page number
     * @param int $perPage Records per page
     * @return array Array of booking objects
     */
    public function getByGuideId($guideId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   u.name as user_name,
                   u.email as user_email,
                   u.phone as user_phone
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.guide_id = :guide_id
            ORDER BY b.date DESC
            LIMIT :offset, :limit
        ");
        
        $stmt->bindValue(':guide_id', $guideId);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count bookings for a guide
     * 
     * @param int $guideId Guide ID
     * @return int Number of bookings
     */
    public function countByGuideId($guideId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM bookings WHERE guide_id = :guide_id
        ");
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Create a new booking
     * 
     * @param array $data Booking data
     * @return int|false The new booking ID or false on failure
     */
    public function create($data) {
        try {
            // Calculate end time based on start time and duration
            $startTime = new DateTime($data['start_time']);
            $endTime = clone $startTime;
            $endTime->add(new DateInterval('PT' . $data['duration'] . 'H'));
            
            // Format times for database
            $startTimeStr = $startTime->format('H:i:s');
            $endTimeStr = $endTime->format('H:i:s');
            
            // Calculate total amount
            $hourlyRate = $data['hourly_rate'] ?? 0;
            $duration = $data['duration'] ?? 0;
            $numPeople = $data['num_people'] ?? 1;
            
            // Base price calculation
            $basePrice = $hourlyRate * $duration;
            
            // Large group fee (if more than 5 people)
            $largeGroupFee = $numPeople > 5 ? ($numPeople - 5) * 10 : 0;
            
            // Service fee (10% of base price)
            $serviceFee = $basePrice * 0.1;
            
            // Total amount
            $totalAmount = $basePrice + $largeGroupFee + $serviceFee;
            
            $stmt = $this->db->prepare("
                INSERT INTO bookings (
                    user_id, guide_id, date, start_time, end_time, duration,
                    tour_type, num_people, meeting_point, special_requests,
                    status, total_amount, payment_status
                ) VALUES (
                    :user_id, :guide_id, :date, :start_time, :end_time, :duration,
                    :tour_type, :num_people, :meeting_point, :special_requests,
                    :status, :total_amount, :payment_status
                )
            ");
            
            $stmt->execute([
                'user_id' => $data['user_id'],
                'guide_id' => $data['guide_id'],
                'date' => $data['date'],
                'start_time' => $startTimeStr,
                'end_time' => $endTimeStr,
                'duration' => $data['duration'],
                'tour_type' => $data['tour_type'] ?? null,
                'num_people' => $data['num_people'] ?? 1,
                'meeting_point' => $data['meeting_point'] ?? null,
                'special_requests' => $data['special_requests'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'total_amount' => $totalAmount,
                'payment_status' => $data['payment_status'] ?? 'pending'
            ]);
            
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error creating booking: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update booking status
     * 
     * @param int $id Booking ID
     * @param string $status New status (pending, confirmed, cancelled, completed)
     * @return bool Success status
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("
            UPDATE bookings
            SET status = :status, updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
    
    /**
     * Update payment status
     * 
     * @param int $id Booking ID
     * @param string $status New payment status (pending, paid, refunded)
     * @return bool Success status
     */
    public function updatePaymentStatus($id, $status) {
        $stmt = $this->db->prepare("
            UPDATE bookings
            SET payment_status = :status, updated_at = NOW()
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
    
    /**
     * Check if booking exists by user, guide, date and time
     * 
     * @param int $userId User ID
     * @param int $guideId Guide ID
     * @param string $date Booking date
     * @param string $startTime Booking start time
     * @return bool True if booking exists, false otherwise
     */
    public function exists($userId, $guideId, $date, $startTime) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM bookings
            WHERE user_id = :user_id
            AND guide_id = :guide_id
            AND date = :date
            AND start_time = :start_time
        ");
        
        $stmt->execute([
            'user_id' => $userId,
            'guide_id' => $guideId,
            'date' => $date,
            'start_time' => $startTime
        ]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Check if guide is available at the requested time
     * 
     * @param int $guideId Guide ID
     * @param string $date Booking date
     * @param string $startTime Booking start time
     * @param string $endTime Booking end time
     * @param int $excludeBookingId Booking ID to exclude from check (for updates)
     * @return bool True if guide is available, false otherwise
     */
    public function isGuideAvailable($guideId, $date, $startTime, $endTime, $excludeBookingId = null) {
        // Check if there are any overlapping bookings
        $sql = "
            SELECT COUNT(*) FROM bookings
            WHERE guide_id = :guide_id
            AND date = :date
            AND status != 'cancelled'
            AND (
                (start_time <= :start_time AND end_time > :start_time) OR
                (start_time < :end_time AND end_time >= :end_time) OR
                (start_time >= :start_time AND end_time <= :end_time)
            )
        ";
        
        if ($excludeBookingId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        
        $params = [
            'guide_id' => $guideId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime
        ];
        
        if ($excludeBookingId) {
            $params['exclude_id'] = $excludeBookingId;
        }
        
        $stmt->execute($params);
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Guide already has a booking at this time
        }
        
        // Check if guide has availability for this day and time
        $dayOfWeek = date('l', strtotime($date)); // Get day name (Monday, Tuesday, etc.)
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM guide_availability
            WHERE guide_id = :guide_id
            AND day = :day
            AND start_time <= :start_time
            AND end_time >= :end_time
        ");
        
        $stmt->execute([
            'guide_id' => $guideId,
            'day' => $dayOfWeek,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
        
        return $stmt->fetchColumn() > 0; // Guide has availability at this time
    }
    
    /**
     * Get upcoming bookings for a guide
     * 
     * @param int $guideId Guide ID
     * @param int $limit Number of bookings to return
     * @return array Array of booking objects
     */
    public function getUpcomingByGuideId($guideId, $limit = 5) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   u.name as user_name,
                   u.email as user_email
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.guide_id = :guide_id
            AND b.date >= CURRENT_DATE
            AND b.status IN ('pending', 'confirmed')
            ORDER BY b.date ASC, b.start_time ASC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':guide_id', $guideId);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get upcoming bookings for a user
     * 
     * @param int $userId User ID
     * @param int $limit Number of bookings to return
     * @return array Array of booking objects
     */
    public function getUpcomingByUserId($userId, $limit = 5) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   gu.name as guide_name,
                   g.speciality as guide_speciality
            FROM bookings b
            JOIN guides g ON b.guide_id = g.id
            JOIN users gu ON g.user_id = gu.id
            WHERE b.user_id = :user_id
            AND b.date >= CURRENT_DATE
            AND b.status IN ('pending', 'confirmed')
            ORDER BY b.date ASC, b.start_time ASC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
} 