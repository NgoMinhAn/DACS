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
     * Get the number of approved reviews for a guide
     * 
     * @param int $guideId The guide ID
     * @return int The number of approved reviews
     */
    public function getApprovedReviewCount($guideId) {
        $this->db->query('
            SELECT COUNT(*) as count 
            FROM guide_reviews 
            WHERE guide_id = :guide_id AND status = "approved"
        ');
        $this->db->bind(':guide_id', $guideId);
        
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }
    
    /**
     * Get reviews for a guide (approved only)
     * 
     * @param int $guideId The guide ID
     * @param int $limit Optional limit for pagination
     * @param int $offset Optional offset for pagination
     * @return array The reviews
     */
    public function getGuideReviews($guideId, $limit = null, $offset = null) {
        $sql = '
            SELECT r.*, u.name 
            FROM guide_reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.guide_id = :guide_id AND r.status = "approved"
            ORDER BY r.created_at DESC
        ';
        
        // Add limit and offset for pagination if provided
        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
            if ($offset !== null) {
                $sql .= ' OFFSET :offset';
            }
        }
        
        $this->db->query($sql);
        $this->db->bind(':guide_id', $guideId);
        
        if ($limit !== null) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            }
        }
        
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
        $this->db->query("SELECT * FROM specialties WHERE name NOT IN ('Historical', 'Off-Beaten Path', 'Off The Beaten Path', 'History') ORDER BY name");
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
    
    /**
     * Get top rated guides
     * 
     * @param int $limit Number of guides to return
     * @return array
     */
    public function getTopRatedGuides($limit = 4) {
        $this->db->query("
            SELECT * FROM guide_listings
            WHERE verified = 1 AND total_reviews > 0
            ORDER BY avg_rating DESC, total_reviews DESC
            LIMIT :limit
        ");
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get guides by specialty or category
     * 
     * @param string $specialty The specialty or category name
     * @param int $limit Optional limit for pagination
     * @param int $offset Optional offset for pagination
     * @return array The guides with the specified specialty
     */
    public function getGuidesBySpecialty($specialty, $limit = null, $offset = null) {
        // Debug log for troubleshooting
        error_log("Searching for guides with specialty: " . $specialty);
        
        // First try an exact match with the whole word, which is more likely to work
        $sql = "
            SELECT * FROM guide_listings 
            WHERE specialties = :exact_specialty
               OR specialties LIKE :start_specialty
               OR specialties LIKE :middle_specialty
               OR specialties LIKE :end_specialty
            ORDER BY 
               CASE 
                  WHEN specialties = :exact_specialty THEN 1
                  WHEN specialties LIKE :start_specialty THEN 2
                  WHEN specialties LIKE :end_specialty THEN 3
                  ELSE 4
               END,
               avg_rating DESC
        ";
        
        // Add limit and offset for pagination if provided
        if ($limit !== null) {
            $sql .= ' LIMIT :limit';
            if ($offset !== null) {
                $sql .= ' OFFSET :offset';
            }
        }
        
        $this->db->query($sql);
        $this->db->bind(':exact_specialty', $specialty);
        $this->db->bind(':start_specialty', $specialty . ',%');
        $this->db->bind(':middle_specialty', '%, ' . $specialty . ',%');
        $this->db->bind(':end_specialty', '%, ' . $specialty);
        
        if ($limit !== null) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            }
        }
        
        $result = $this->db->resultSet();
        
        // If we didn't find anything, try a more flexible LIKE search
        if (empty($result)) {
            error_log("No exact matches found, trying flexible search for: " . $specialty);
            
            $sql = "
                SELECT * FROM guide_listings 
                WHERE LOWER(specialties) LIKE LOWER(:flexible_specialty)
                ORDER BY avg_rating DESC
            ";
            
            // Add limit and offset for pagination if provided
            if ($limit !== null) {
                $sql .= ' LIMIT :limit';
                if ($offset !== null) {
                    $sql .= ' OFFSET :offset';
                }
            }
            
            $this->db->query($sql);
            $this->db->bind(':flexible_specialty', '%' . $specialty . '%');
            
            if ($limit !== null) {
                $this->db->bind(':limit', $limit, PDO::PARAM_INT);
                if ($offset !== null) {
                    $this->db->bind(':offset', $offset, PDO::PARAM_INT);
                }
            }
            
            $result = $this->db->resultSet();
        }
        
        error_log("Found " . count($result) . " guides for specialty: " . $specialty);
        
        return $result;
    }
} 