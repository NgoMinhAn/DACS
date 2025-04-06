<?php
/**
 * Guide Model
 * Handles all guide-related database operations
 */
class Guide {
    private $db;
    
    /**
     * Constructor - initialize database connection
     */
    public function __construct() {
        $this->db = getDbConnection();
    }
    
    /**
     * Find guide by ID
     * 
     * @param int $id Guide ID
     * @return object|false Guide object or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT g.*, u.name, u.email, u.profile_image, u.phone, u.bio as user_bio 
            FROM guides g
            JOIN users u ON g.user_id = u.id
            WHERE g.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Find guide by user ID
     * 
     * @param int $userId User ID
     * @return object|false Guide object or false if not found
     */
    public function findByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT g.*, u.name, u.email, u.profile_image, u.phone, u.bio as user_bio 
            FROM guides g
            JOIN users u ON g.user_id = u.id
            WHERE g.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Get all guides with pagination
     * 
     * @param int $page Page number
     * @param int $perPage Records per page
     * @return array Array of guide objects
     */
    public function getAll($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT g.*, u.name, u.email, u.profile_image 
            FROM guides g
            JOIN users u ON g.user_id = u.id
            ORDER BY g.rating DESC
            LIMIT :offset, :limit
        ");
        
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count total number of guides
     * 
     * @return int Total number of guides
     */
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM guides");
        return $stmt->fetchColumn();
    }
    
    /**
     * Search guides with filters
     * 
     * @param array $filters Search criteria
     * @param int $page Page number
     * @param int $perPage Records per page
     * @return array Array of guide objects
     */
    public function search($filters = [], $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];
        
        // Add location filter
        if (!empty($filters['location'])) {
            $conditions[] = "g.location LIKE :location";
            $params['location'] = '%' . $filters['location'] . '%';
        }
        
        // Add specialty filter
        if (!empty($filters['specialty'])) {
            $conditions[] = "(g.speciality LIKE :specialty OR EXISTS (
                SELECT 1 FROM guide_specialties gs 
                WHERE gs.guide_id = g.id AND gs.specialty LIKE :specialty
            ))";
            $params['specialty'] = '%' . $filters['specialty'] . '%';
        }
        
        // Add language filter
        if (!empty($filters['language'])) {
            $conditions[] = "EXISTS (
                SELECT 1 FROM guide_languages gl 
                WHERE gl.guide_id = g.id AND gl.language = :language
            )";
            $params['language'] = $filters['language'];
        }
        
        // Add category filter
        if (!empty($filters['category_id'])) {
            $conditions[] = "g.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }
        
        // Add minimum rating filter
        if (!empty($filters['min_rating'])) {
            $conditions[] = "g.rating >= :min_rating";
            $params['min_rating'] = $filters['min_rating'];
        }
        
        // Build the WHERE clause
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        // Build the complete query
        $sql = "
            SELECT g.*, u.name, u.email, u.profile_image 
            FROM guides g
            JOIN users u ON g.user_id = u.id
            $whereClause
            ORDER BY g.rating DESC
            LIMIT :offset, :limit
        ";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind all the parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Count search results
     * 
     * @param array $filters Search criteria
     * @return int Number of matching guides
     */
    public function countSearch($filters = []) {
        $conditions = [];
        $params = [];
        
        // Add location filter
        if (!empty($filters['location'])) {
            $conditions[] = "g.location LIKE :location";
            $params['location'] = '%' . $filters['location'] . '%';
        }
        
        // Add specialty filter
        if (!empty($filters['specialty'])) {
            $conditions[] = "(g.speciality LIKE :specialty OR EXISTS (
                SELECT 1 FROM guide_specialties gs 
                WHERE gs.guide_id = g.id AND gs.specialty LIKE :specialty
            ))";
            $params['specialty'] = '%' . $filters['specialty'] . '%';
        }
        
        // Add language filter
        if (!empty($filters['language'])) {
            $conditions[] = "EXISTS (
                SELECT 1 FROM guide_languages gl 
                WHERE gl.guide_id = g.id AND gl.language = :language
            )";
            $params['language'] = $filters['language'];
        }
        
        // Add category filter
        if (!empty($filters['category_id'])) {
            $conditions[] = "g.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }
        
        // Add minimum rating filter
        if (!empty($filters['min_rating'])) {
            $conditions[] = "g.rating >= :min_rating";
            $params['min_rating'] = $filters['min_rating'];
        }
        
        // Build the WHERE clause
        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        // Build the complete query
        $sql = "
            SELECT COUNT(*) 
            FROM guides g
            JOIN users u ON g.user_id = u.id
            $whereClause
        ";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind all the parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     * Create a new guide
     * 
     * @param array $data Guide data
     * @return int|false The new guide ID or false on failure
     */
    public function create($data) {
        $this->db->beginTransaction();
        
        try {
            // Insert into guides table
            $stmt = $this->db->prepare("
                INSERT INTO guides (user_id, category_id, speciality, hourly_rate, experience, location, bio)
                VALUES (:user_id, :category_id, :speciality, :hourly_rate, :experience, :location, :bio)
            ");
            
            $stmt->execute([
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id'] ?? null,
                'speciality' => $data['speciality'] ?? null,
                'hourly_rate' => $data['hourly_rate'],
                'experience' => $data['experience'] ?? null,
                'location' => $data['location'] ?? null,
                'bio' => $data['bio'] ?? null
            ]);
            
            $guideId = $this->db->lastInsertId();
            
            // Update user to be a guide
            $stmt = $this->db->prepare("
                UPDATE users SET is_guide = 1 WHERE id = :user_id
            ");
            $stmt->execute(['user_id' => $data['user_id']]);
            
            // Add languages if provided
            if (!empty($data['languages']) && is_array($data['languages'])) {
                $stmt = $this->db->prepare("
                    INSERT INTO guide_languages (guide_id, language, proficiency)
                    VALUES (:guide_id, :language, :proficiency)
                ");
                
                foreach ($data['languages'] as $language) {
                    $stmt->execute([
                        'guide_id' => $guideId,
                        'language' => $language['language'],
                        'proficiency' => $language['proficiency'] ?? 'conversational'
                    ]);
                }
            }
            
            // Add specialties if provided
            if (!empty($data['specialties']) && is_array($data['specialties'])) {
                $stmt = $this->db->prepare("
                    INSERT INTO guide_specialties (guide_id, specialty)
                    VALUES (:guide_id, :specialty)
                ");
                
                foreach ($data['specialties'] as $specialty) {
                    $stmt->execute([
                        'guide_id' => $guideId,
                        'specialty' => $specialty
                    ]);
                }
            }
            
            // Add availability if provided
            if (!empty($data['availability']) && is_array($data['availability'])) {
                $stmt = $this->db->prepare("
                    INSERT INTO guide_availability (guide_id, day, start_time, end_time)
                    VALUES (:guide_id, :day, :start_time, :end_time)
                ");
                
                foreach ($data['availability'] as $slot) {
                    $stmt->execute([
                        'guide_id' => $guideId,
                        'day' => $slot['day'],
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time']
                    ]);
                }
            }
            
            $this->db->commit();
            return $guideId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error creating guide: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update guide information
     * 
     * @param int $id Guide ID
     * @param array $data Guide data to update
     * @return bool Success status
     */
    public function update($id, $data) {
        $this->db->beginTransaction();
        
        try {
            // Update guides table
            $columns = [];
            $params = [];
            
            foreach ($data as $key => $value) {
                // Skip non-column data
                if (in_array($key, ['languages', 'specialties', 'availability'])) {
                    continue;
                }
                
                $columns[] = "$key = :$key";
                $params[$key] = $value;
            }
            
            if (!empty($columns)) {
                $sql = "UPDATE guides SET " . implode(", ", $columns) . ", updated_at = NOW() WHERE id = :id";
                $params['id'] = $id;
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
            }
            
            // Update languages if provided
            if (!empty($data['languages']) && is_array($data['languages'])) {
                // Remove existing languages
                $stmt = $this->db->prepare("DELETE FROM guide_languages WHERE guide_id = :guide_id");
                $stmt->execute(['guide_id' => $id]);
                
                // Add new languages
                $stmt = $this->db->prepare("
                    INSERT INTO guide_languages (guide_id, language, proficiency)
                    VALUES (:guide_id, :language, :proficiency)
                ");
                
                foreach ($data['languages'] as $language) {
                    $stmt->execute([
                        'guide_id' => $id,
                        'language' => $language['language'],
                        'proficiency' => $language['proficiency'] ?? 'conversational'
                    ]);
                }
            }
            
            // Update specialties if provided
            if (!empty($data['specialties']) && is_array($data['specialties'])) {
                // Remove existing specialties
                $stmt = $this->db->prepare("DELETE FROM guide_specialties WHERE guide_id = :guide_id");
                $stmt->execute(['guide_id' => $id]);
                
                // Add new specialties
                $stmt = $this->db->prepare("
                    INSERT INTO guide_specialties (guide_id, specialty)
                    VALUES (:guide_id, :specialty)
                ");
                
                foreach ($data['specialties'] as $specialty) {
                    $stmt->execute([
                        'guide_id' => $id,
                        'specialty' => $specialty
                    ]);
                }
            }
            
            // Update availability if provided
            if (!empty($data['availability']) && is_array($data['availability'])) {
                // Remove existing availability
                $stmt = $this->db->prepare("DELETE FROM guide_availability WHERE guide_id = :guide_id");
                $stmt->execute(['guide_id' => $id]);
                
                // Add new availability
                $stmt = $this->db->prepare("
                    INSERT INTO guide_availability (guide_id, day, start_time, end_time)
                    VALUES (:guide_id, :day, :start_time, :end_time)
                ");
                
                foreach ($data['availability'] as $slot) {
                    $stmt->execute([
                        'guide_id' => $id,
                        'day' => $slot['day'],
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time']
                    ]);
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error updating guide: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get guide's languages
     * 
     * @param int $guideId Guide ID
     * @return array Array of language objects
     */
    public function getLanguages($guideId) {
        $stmt = $this->db->prepare("
            SELECT language, proficiency 
            FROM guide_languages 
            WHERE guide_id = :guide_id
        ");
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get guide's specialties
     * 
     * @param int $guideId Guide ID
     * @return array Array of specialty strings
     */
    public function getSpecialties($guideId) {
        $stmt = $this->db->prepare("
            SELECT specialty 
            FROM guide_specialties 
            WHERE guide_id = :guide_id
        ");
        $stmt->execute(['guide_id' => $guideId]);
        return array_map(function($row) {
            return $row->specialty;
        }, $stmt->fetchAll());
    }
    
    /**
     * Get guide's availability
     * 
     * @param int $guideId Guide ID
     * @return array Array of availability objects
     */
    public function getAvailability($guideId) {
        $stmt = $this->db->prepare("
            SELECT day, start_time, end_time 
            FROM guide_availability 
            WHERE guide_id = :guide_id
            ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
        ");
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all categories
     * 
     * @return array Array of category objects
     */
    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM guide_categories ORDER BY name");
        return $stmt->fetchAll();
    }
    
    /**
     * Get guide by ID with all related data
     * 
     * @param int $id Guide ID
     * @return object|false Guide object with all related data or false if not found
     */
    public function getComplete($id) {
        $guide = $this->findById($id);
        
        if (!$guide) {
            return false;
        }
        
        // Get languages
        $guide->languages = $this->getLanguages($id);
        
        // Get specialties
        $guide->specialties = $this->getSpecialties($id);
        
        // Get availability
        $guide->availability = $this->getAvailability($id);
        
        return $guide;
    }
    
    /**
     * Get user object for a guide
     * 
     * @param object $guide Guide object
     * @return object User data for the guide
     */
    public function getUser() {
        if (!isset($this->name)) {
            // If this is a database fetched object, the name should already be there
            return $this;
        }
        
        // Otherwise fetch from database
        $user = new User();
        return $user->findById($this->user_id);
    }
    
    /**
     * Update guide rating based on reviews
     * 
     * @param int $guideId Guide ID
     * @return bool Success status
     */
    public function updateRating($guideId) {
        $stmt = $this->db->prepare("
            SELECT AVG(rating) as avg_rating, COUNT(*) as count
            FROM reviews
            WHERE guide_id = :guide_id
        ");
        $stmt->execute(['guide_id' => $guideId]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return false;
        }
        
        $stmt = $this->db->prepare("
            UPDATE guides
            SET rating = :rating, review_count = :count
            WHERE id = :guide_id
        ");
        
        return $stmt->execute([
            'guide_id' => $guideId,
            'rating' => $result->avg_rating ?: 0,
            'count' => $result->count
        ]);
    }
    
    /**
     * Get featured guides (highest rated)
     * 
     * @param int $limit Number of guides to fetch
     * @return array Array of guide objects
     */
    public function getFeatured($limit = 4) {
        $stmt = $this->db->prepare("
            SELECT g.*, u.name, u.location, 
                   ROUND(AVG(r.rating), 1) as avg_rating, 
                   COUNT(r.id) as review_count
            FROM guides g
            JOIN users u ON g.user_id = u.id
            LEFT JOIN reviews r ON g.id = r.guide_id
            GROUP BY g.id
            ORDER BY avg_rating DESC, review_count DESC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
} 