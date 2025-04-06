<?php
/**
 * Review Model
 * Handles all review-related database operations
 */
class Review {
    public $id;
    public $user_id;
    public $guide_id;
    public $booking_id;
    public $rating;
    public $comment;
    public $created_at;
    public $updated_at;
    
    private $db;
    
    /**
     * Constructor - initialize database connection and optionally set properties
     * 
     * @param int|null $id Review ID
     * @param int|null $user_id User ID
     * @param int|null $guide_id Guide ID
     * @param int|null $booking_id Booking ID
     * @param float|null $rating Rating value
     * @param string|null $comment Review comment
     * @param string|null $created_at Created timestamp
     * @param string|null $updated_at Updated timestamp
     */
    public function __construct($id = null, $user_id = null, $guide_id = null, 
                               $booking_id = null, $rating = null, $comment = null, 
                               $created_at = null, $updated_at = null) {
        $this->db = getDbConnection();
        
        if ($id !== null) {
            $this->id = $id;
            $this->user_id = $user_id;
            $this->guide_id = $guide_id;
            $this->booking_id = $booking_id;
            $this->rating = $rating;
            $this->comment = $comment;
            $this->created_at = $created_at ?? date('Y-m-d H:i:s');
            $this->updated_at = $updated_at ?? date('Y-m-d H:i:s');
        }
    }
    
    /**
     * Find review by ID
     * 
     * @param int $id Review ID
     * @return array|false Review data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Find review by booking ID
     * 
     * @param int $bookingId Booking ID
     * @return array|false Review data or false if not found
     */
    public function findByBookingId($bookingId) {
        $stmt = $this->db->prepare("SELECT * FROM reviews WHERE booking_id = :booking_id");
        $stmt->execute(['booking_id' => $bookingId]);
        return $stmt->fetch();
    }
    
    /**
     * Get reviews for a guide
     * 
     * @param int $guideId Guide ID
     * @param int $page Page number
     * @param int $perPage Reviews per page
     * @return array Array of review objects
     */
    public function getByGuideId($guideId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT r.*, u.name as user_name, u.profile_image as user_image
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.guide_id = :guide_id
            ORDER BY r.created_at DESC
            LIMIT :offset, :limit
        ");
        
        $stmt->bindValue(':guide_id', $guideId);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count reviews for a guide
     * 
     * @param int $guideId Guide ID
     * @return int Number of reviews
     */
    public function countByGuideId($guideId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM reviews WHERE guide_id = :guide_id
        ");
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get reviews by a user
     * 
     * @param int $userId User ID
     * @param int $page Page number
     * @param int $perPage Reviews per page
     * @return array Array of review objects
     */
    public function getByUserId($userId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT r.*, g.title as guide_title, gu.name as guide_name
            FROM reviews r
            JOIN guides g ON r.guide_id = g.id
            JOIN users gu ON g.user_id = gu.id
            WHERE r.user_id = :user_id
            ORDER BY r.created_at DESC
            LIMIT :offset, :limit
        ");
        
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count reviews by a user
     * 
     * @param int $userId User ID
     * @return int Number of reviews
     */
    public function countByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM reviews WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get latest reviews
     * 
     * @param int $limit Number of reviews to return
     * @return array Array of review objects
     */
    public function getLatest($limit = 3) {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                   u.name as user_name, 
                   u.profile_image as user_image,
                   gu.name as guide_name
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            JOIN guides g ON r.guide_id = g.id
            JOIN users gu ON g.user_id = gu.id
            ORDER BY r.created_at DESC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Create a new review
     * 
     * @param array $data Review data
     * @return int|false The new review ID or false on failure
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO reviews (user_id, guide_id, booking_id, rating, comment, created_at, updated_at)
            VALUES (:user_id, :guide_id, :booking_id, :rating, :comment, NOW(), NOW())
        ");
        
        $result = $stmt->execute([
            'user_id' => $data['user_id'],
            'guide_id' => $data['guide_id'],
            'booking_id' => $data['booking_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment']
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update a review
     * 
     * @param int $id Review ID
     * @param array $data Review data
     * @return bool Success status
     */
    public function update($id, $data) {
        $sql = "UPDATE reviews SET ";
        $params = [];
        
        foreach ($data as $key => $value) {
            $sql .= "$key = :$key, ";
            $params[$key] = $value;
        }
        
        $sql .= "updated_at = NOW() WHERE id = :id";
        $params['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete a review
     * 
     * @param int $id Review ID
     * @return bool Success status
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    // Get user object associated with this review
    public function getUser() {
        return User::findById($this->user_id);
    }
    
    // Get guide object associated with this review
    public function getGuide() {
        return TourGuide::findById($this->guide_id);
    }
    
    // Get booking object associated with this review
    public function getBooking() {
        return Booking::findById($this->booking_id);
    }
} 