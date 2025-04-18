<?php
/**
 * Guide Model
 * Handles all database operations related to tour guides
 */
class GuideModel {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Get all guides
     * 
     * @param array $filters Optional filters for guides
     * @return array
     */
    public function getAllGuides($filters = []) {
        // Start with base query using the guide_listings view
        $sql = "SELECT * FROM guide_listings WHERE 1=1";
        
        // Apply filters if provided
        if (!empty($filters)) {
            // Filter by language
            if (!empty($filters['language'])) {
                $sql .= " AND languages LIKE :language";
            }
            
            // Filter by specialty
            if (!empty($filters['specialty'])) {
                $sql .= " AND specialties LIKE :specialty";
            }
            
            // Filter by price range
            if (!empty($filters['price_range'])) {
                switch ($filters['price_range']) {
                    case '0-25':
                        $sql .= " AND hourly_rate < 25";
                        break;
                    case '25-50':
                        $sql .= " AND hourly_rate >= 25 AND hourly_rate <= 50";
                        break;
                    case '50-100':
                        $sql .= " AND hourly_rate > 50 AND hourly_rate <= 100";
                        break;
                    case '100+':
                        $sql .= " AND hourly_rate > 100";
                        break;
                }
            }
        }
        
        // Order by featured and rating
        $sql .= " ORDER BY featured DESC, avg_rating DESC";
        
        // Prepare and execute query
        $this->db->query($sql);
        
        // Bind filter values if provided
        if (!empty($filters)) {
            if (!empty($filters['language'])) {
                $this->db->bind(':language', '%' . $filters['language'] . '%');
            }
            
            if (!empty($filters['specialty'])) {
                $this->db->bind(':specialty', '%' . $filters['specialty'] . '%');
            }
        }
        
        // Execute and return results
        return $this->db->resultSet();
    }
    
    /**
     * Get guide by ID
     * 
     * @param int $id The guide ID
     * @return object
     */
    public function getGuideById($id) {
        $this->db->query("SELECT * FROM guide_listings WHERE guide_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Get reviews for a guide
     * 
     * @param int $guideId The guide ID
     * @return array The reviews
     */
    public function getGuideReviews($guideId) {
        $this->db->query('
            SELECT r.*, u.name 
            FROM guide_reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.guide_id = :guide_id AND r.status = "approved"
            ORDER BY r.created_at DESC
        ');
        $this->db->bind(':guide_id', $guideId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get guide specialties
     * 
     * @param int $guideId The guide ID
     * @return array
     */
    public function getGuideSpecialties($guideId) {
        $this->db->query("
            SELECT s.*
            FROM guide_specialties gs
            JOIN specialties s ON gs.specialty_id = s.id
            WHERE gs.guide_id = :guide_id
        ");
        
        $this->db->bind(':guide_id', $guideId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get guide languages
     * 
     * @param int $guideId The guide ID
     * @return array
     */
    public function getGuideLanguages($guideId) {
        $this->db->query("
            SELECT l.*, gl.fluency_level
            FROM guide_languages gl
            JOIN languages l ON gl.language_id = l.id
            WHERE gl.guide_id = :guide_id
        ");
        
        $this->db->bind(':guide_id', $guideId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get all available specialties
     * 
     * @return array
     */
    public function getAllSpecialties() {
        $this->db->query("SELECT * FROM specialties ORDER BY name");
        return $this->db->resultSet();
    }
    
    /**
     * Get all available languages
     * 
     * @return array
     */
    public function getAllLanguages() {
        $this->db->query("SELECT * FROM languages ORDER BY name");
        return $this->db->resultSet();
    }
    
    /**
     * Get featured guides
     * 
     * @param int $limit Number of guides to return
     * @return array
     */
    public function getFeaturedGuides($limit = 4) {
        $this->db->query("
            SELECT * FROM guide_listings
            WHERE featured = 1
            ORDER BY avg_rating DESC
            LIMIT :limit
        ");
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
} 