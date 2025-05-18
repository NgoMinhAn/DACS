<?php
class ReviewModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Thêm review mới
    public function addReview($data) {
        $this->db->query('INSERT INTO guide_reviews (user_id, guide_id, rating, review_text, created_at, status) 
                         VALUES (:user_id, :guide_id, :rating, :review_text, :created_at, "approved")');
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':guide_id', $data['guide_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':review_text', $data['review_text']);
        $this->db->bind(':created_at', $data['created_at']);

        return $this->db->execute();
    }

    // Kiểm tra xem user đã review guide chưa
    public function getReviewByUserAndGuide($user_id, $guide_id) {
        $this->db->query('SELECT * FROM guide_reviews WHERE user_id = :user_id AND guide_id = :guide_id');
        
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':guide_id', $guide_id);

        return $this->db->single();
    }

    // Lấy tất cả review của một guide
    public function getReviewsByGuideId($guide_id) {
        $this->db->query('SELECT r.*, u.name as user_name 
                         FROM guide_reviews r 
                         JOIN users u ON r.user_id = u.id 
                         WHERE r.guide_id = :guide_id
                         ORDER BY r.created_at DESC');
        
        $this->db->bind(':guide_id', $guide_id);

        return $this->db->resultSet();
    }

    // Lấy thống kê rating của guide
    public function getGuideRatingStats($guide_id) {
        $this->db->query('SELECT 
                            COUNT(*) as total_reviews,
                            AVG(rating) as avg_rating,
                            SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star_count,
                            SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star_count,
                            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star_count,
                            SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star_count,
                            SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star_count
                         FROM guide_reviews 
                         WHERE guide_id = :guide_id');
        
        $this->db->bind(':guide_id', $guide_id);

        return $this->db->single();
    }
} 