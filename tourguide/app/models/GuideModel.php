<?php
/**
 * Guide Model
 * Handles all database operations related to tour guides
 */
class GuideModel
{
    private $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all guides
     * 
     * @param array $filters Optional filters for guides
     * @return array
     */
    public function getAllGuides($filters = [])
    {
        // Start with base query using the guide_listings view
        $sql = "SELECT * FROM guide_listings WHERE 1=1";

        // Debug incoming filters
        error_log('[DEBUG][GuideModel::getAllGuides] incoming filters: ' . json_encode($filters));

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

            // Filter by minimum rating
            if (!empty($filters['rating']) && is_numeric($filters['rating'])) {
                $sql .= " AND avg_rating >= :rating";
            }
        }

        // Order by featured and rating
        $sql .= " ORDER BY featured DESC, avg_rating DESC";

        // Prepare and execute query
        $this->db->query($sql);

        // Bind filter values if provided
        if (!empty($filters)) {
            if (!empty($filters['language'])) {
                $langBind = '%' . $filters['language'] . '%';
                error_log('[DEBUG][GuideModel::getAllGuides] binding :language => ' . $langBind);
                $this->db->bind(':language', $langBind);
            }

            if (!empty($filters['specialty'])) {
                $specBind = '%' . $filters['specialty'] . '%';
                error_log('[DEBUG][GuideModel::getAllGuides] binding :specialty => ' . $specBind);
                $this->db->bind(':specialty', $specBind);
            }

            if (!empty($filters['rating']) && is_numeric($filters['rating'])) {
                $minRating = (float)$filters['rating'];
                error_log('[DEBUG][GuideModel::getAllGuides] binding :rating => ' . $minRating);
                $this->db->bind(':rating', $minRating);
            }
        }

        // Log final SQL for debugging
        error_log('[DEBUG][GuideModel::getAllGuides] final SQL: ' . $sql);

        // Execute and return results
        return $this->db->resultSet();
    }


    /**
     * Get the number of approved reviews for a guide
     * 
     * @param int $guideId The guide ID
     * @return int The number of approved reviews
     */
    public function getApprovedReviewCount($guideId)
    {
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
    public function getGuideReviews($guideId, $limit = null, $offset = null)
    {
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
    public function getGuideSpecialties($guideId)
    {
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
    public function getGuideLanguages($guideId)
    {
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
    public function getAllSpecialties()
    {
        $this->db->query("SELECT * FROM specialties WHERE name NOT IN ('Historical', 'Off-Beaten Path', 'Off The Beaten Path', 'History') ORDER BY name");
        return $this->db->resultSet();
    }

    /**
     * Get all available languages
     * 
     * @return array
     */
    public function getAllLanguages()
    {
        $this->db->query("SELECT * FROM languages ORDER BY name");
        return $this->db->resultSet();
    }

    /**
     * Get featured guides
     * 
     * @param int $limit Number of guides to return
     * @return array
     */
    public function getFeaturedGuides($limit = 4)
    {
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
    public function getTopRatedGuides($limit = 4)
    {
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
     * Score a guide based on how well it matches user hobbies
     * 
     * @param object $guide The guide object
     * @param array $hobbies Array of user hobbies (normalized keywords)
     * @return float The match score
     */
    private function scoreGuideByHobbies($guide, $hobbies)
    {
        $score = 0;
        
        // Normalize guide data for matching
        $guideSpecialties = strtolower($guide->specialties ?? '');
        $guideBio = strtolower($guide->bio ?? '');
        $guideLocation = strtolower($guide->location ?? '');
        
        // Score each hobby match
        foreach ($hobbies as $hobby) {
            $hobbyLower = strtolower(trim($hobby));
            if (empty($hobbyLower)) continue;
            
            $hobbyMatched = false;
            
            // Check specialties first (highest weight)
            if (!empty($guideSpecialties)) {
                // Split specialties by comma and check each one
                $specialtyList = array_map('trim', explode(',', $guideSpecialties));
                foreach ($specialtyList as $specialty) {
                    $specialtyLower = strtolower($specialty);
                    // Exact match in specialty (highest weight: 10 points)
                    if ($specialtyLower === $hobbyLower) {
                        $score += 10;
                        $hobbyMatched = true;
                        break; // Only count once per hobby per specialty
                    }
                    // Partial match in specialty (high weight: 7 points)
                    elseif (stripos($specialtyLower, $hobbyLower) !== false || stripos($hobbyLower, $specialtyLower) !== false) {
                        $score += 7;
                        $hobbyMatched = true;
                        break; // Only count once per hobby per specialty
                    }
                }
            }
            
            // Match in bio (medium weight: 5 points) - only if not already matched in specialties
            if (!$hobbyMatched && stripos($guideBio, $hobbyLower) !== false) {
                $score += 5;
            }
            
            // Match in location (lower weight: 2 points) - only if not already matched
            if (!$hobbyMatched && stripos($guideLocation, $hobbyLower) !== false) {
                $score += 2;
            }
        }
        
        // Add rating bonus (normalized to 0-5 points)
        $ratingBonus = ($guide->avg_rating ?? 0) * 1.0; // 1 point per rating star
        $score += $ratingBonus;
        
        // Add review count bonus (normalized, max 3 points)
        $reviewBonus = min(($guide->total_reviews ?? 0) / 10, 3); // 0.1 point per review, max 3
        $score += $reviewBonus;
        
        return $score;
    }

    /**
     * Get recommended guides for a user based on their hobbies (using weighted algorithm) or booking history.
     * Falls back to top rated guides when there's no data or hobbies are blank.
     *
     * @param int|null $userId
     * @param int $limit
     * @return array
     */
    public function getRecommendedGuidesForUser($userId = null, $limit = 6)
    {
        // If no user provided, return top rated guides
        if (!$userId) {
            return $this->getTopRatedGuides($limit);
        }

        // 1) First priority: Check if user has hobbies and use weighted algorithm for recommendations
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $userHobbies = $userModel->getUserHobbies($userId);
        
        if (!empty($userHobbies) && trim($userHobbies) !== '') {
            try {
                // Get all available guides
                $allGuides = $this->getAllGuides();
                
                if (!empty($allGuides)) {
                    // Parse hobbies (split by comma, semicolon, or newline)
                    $hobbies = preg_split('/[,;\n\r]+/', $userHobbies);
                    $hobbies = array_map('trim', $hobbies);
                    $hobbies = array_filter($hobbies, function($h) { return !empty($h); });
                    
                    if (!empty($hobbies)) {
                        // Score each guide based on hobby matches
                        $scoredGuides = [];
                        foreach ($allGuides as $guide) {
                            // Only consider verified guides
                            if (!($guide->verified ?? false)) {
                                continue;
                            }
                            
                            $score = $this->scoreGuideByHobbies($guide, $hobbies);
                            
                            // Only include guides with some match (score > 0)
                            if ($score > 0) {
                                $scoredGuides[] = [
                                    'guide' => $guide,
                                    'score' => $score
                                ];
                            }
                        }
                        
                        // Sort by score (descending), then by rating, then by review count
                        usort($scoredGuides, function($a, $b) {
                            if ($a['score'] != $b['score']) {
                                return $b['score'] <=> $a['score']; // Higher score first
                            }
                            // If scores are equal, sort by rating
                            $ratingA = $a['guide']->avg_rating ?? 0;
                            $ratingB = $b['guide']->avg_rating ?? 0;
                            if ($ratingA != $ratingB) {
                                return $ratingB <=> $ratingA; // Higher rating first
                            }
                            // If ratings are equal, sort by review count
                            $reviewsA = $a['guide']->total_reviews ?? 0;
                            $reviewsB = $b['guide']->total_reviews ?? 0;
                            return $reviewsB <=> $reviewsA; // More reviews first
                        });
                        
                        // Extract guides and limit results
                        $recommendedGuides = array_map(function($item) {
                            return $item['guide'];
                        }, array_slice($scoredGuides, 0, $limit));
                        
                        if (!empty($recommendedGuides)) {
                            error_log("[Recommendations] Using weighted algorithm recommendations based on hobbies for user {$userId}");
                            return $recommendedGuides;
                        }
                    }
                }
            } catch (Exception $e) {
                error_log("[Recommendations] Hobby-based recommendation algorithm failed: " . $e->getMessage() . ". Falling back to other methods.");
                // Continue to fallback methods below
            }
        }

        // 2) Fallback: Try to find specialties from the user's past bookings
        $this->db->query('SELECT DISTINCT s.name as specialty
                          FROM bookings b
                          JOIN guide_specialties gs ON b.guide_id = gs.guide_id
                          JOIN specialties s ON gs.specialty_id = s.id
                          WHERE b.user_id = :user_id
                          ORDER BY COUNT(b.id) DESC
                          LIMIT 5');
        $this->db->bind(':user_id', $userId);
        $rows = $this->db->resultSet();

        $specialties = [];
        foreach ($rows as $r) {
            if (!empty($r->specialty)) $specialties[] = $r->specialty;
        }

        // 3) If we have specialties, get guides matching those specialties ordered by rating
        if (!empty($specialties)) {
            // Build parameterized OR conditions for specialties
            $sql = "SELECT * FROM guide_listings WHERE (";
            $binds = [];
            $parts = [];
            foreach ($specialties as $idx => $spec) {
                $parts[] = "specialties LIKE :spec{$idx}";
                $binds[":spec{$idx}"] = '%' . $spec . '%';
            }
            $sql .= implode(' OR ', $parts) . ") AND verified = 1 ORDER BY avg_rating DESC, total_reviews DESC LIMIT :limit";

            $this->db->query($sql);
            foreach ($binds as $k => $v) {
                $this->db->bind($k, $v);
            }
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $result = $this->db->resultSet();

            if (!empty($result)) {
                return $result;
            }
        }

        // 4) No booking-based specialties found - look for user's past searches if a table exists
        $this->db->query('SELECT query FROM user_searches WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5');
        $this->db->bind(':user_id', $userId);
        $searchRows = [];
        try {
            $searchRows = $this->db->resultSet();
        } catch (Exception $e) {
            // Table might not exist; ignore and fall back
            $searchRows = [];
        }

        $queries = [];
        foreach ($searchRows as $sr) {
            if (!empty($sr->query)) $queries[] = $sr->query;
        }

        if (!empty($queries)) {
            // Use the most recent query to search guides
            $q = $queries[0];
            return $this->searchGuides($q);
        }

        // 5) Last resort - return global top rated guides
        return $this->getTopRatedGuides($limit);
    }

    /**
     * Get guides by specialty or category
     * 
     * @param string $specialty The specialty or category name
     * @param int $limit Optional limit for pagination
     * @param int $offset Optional offset for pagination
     * @return array The guides with the specified specialty
     */
    public function getGuidesBySpecialty($specialty, $limit = null, $offset = null)
    {
        // Debug log for troubleshooting
        error_log("Searching for guides with specialty: " . $specialty);

        // Simple LIKE search - most reliable approach
        $sql = "
            SELECT * FROM guide_listings 
            WHERE specialties LIKE :specialty
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
        $this->db->bind(':specialty', '%' . $specialty . '%');

        if ($limit !== null) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            }
        }

        $result = $this->db->resultSet();
        error_log("Found " . count($result) . " guides for specialty: " . $specialty);

        return $result;
    }

    /**
     * Create a new booking
     * @param array $data
     * @return bool
     */
    public function createBooking($data)
    {
        $this->db->query('INSERT INTO bookings (guide_id, user_id, booking_date, start_time, end_time, total_hours, total_price, status, payment_status, special_requests, number_of_people, meeting_location, created_at, updated_at) VALUES (:guide_id, :user_id, :booking_date, :start_time, :end_time, :total_hours, :total_price, :status, :payment_status, :special_requests, :number_of_people, :meeting_location, NOW(), NOW())');
        $this->db->bind(':guide_id', $data['guide_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':booking_date', $data['booking_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':total_hours', $data['total_hours']);
        $this->db->bind(':total_price', $data['total_price']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':payment_status', $data['payment_status']);
        $this->db->bind(':special_requests', $data['special_requests']);
        $this->db->bind(':number_of_people', $data['number_of_people']);
        $this->db->bind(':meeting_location', $data['meeting_location']);
        return $this->db->execute();
    }

    /**
     * Search for guides based on a query
     * 
     * @param string $query The search query
     * @return array The matching guides
     */
    /**
     * Map location code to actual location names in database
     * 
     * @param string $locationCode The location code from form
     * @return array Array of possible location names
     */
    private function mapLocationCode($locationCode)
    {
        $locationMap = [
            'hanoi' => ['Hà Nội', 'Ha Noi', 'Hanoi', 'Hà Nội'],
            'hochiminh' => ['Hồ Chí Minh', 'Ho Chi Minh', 'Ho Chi Minh City', 'Hồ Chí Minh', 'Sài Gòn', 'Sai Gon', 'Saigon'],
            'danang' => ['Đà Nẵng', 'Da Nang', 'Danang'],
            'hue' => ['Huế', 'Hue'],
            'halong' => ['Hạ Long', 'Ha Long', 'Halong', 'Hạ Long Bay'],
            'sapa' => ['Sapa', 'Sa Pa', 'Sapa'],
            'nhatrang' => ['Nha Trang', 'Nha Trang'],
            'dalat' => ['Đà Lạt', 'Da Lat', 'Dalat']
        ];
        
        return isset($locationMap[$locationCode]) ? $locationMap[$locationCode] : [$locationCode];
    }
    
    public function searchGuides($query, $location = null)
    {
        // Build SQL query with named parameters
        $conditions = [];
        
        // If query is provided, search in name, bio, location, and specialties
        if (!empty($query)) {
            $conditions[] = "(name LIKE :query1 OR bio LIKE :query2 OR location LIKE :query3 OR specialties LIKE :query4)";
        }
        
        // Add location filter if provided
        if (!empty($location)) {
            // Map location code to actual location names
            $locationNames = $this->mapLocationCode($location);
            
            // Build OR conditions for all possible location names
            $locationConditions = [];
            foreach ($locationNames as $index => $locName) {
                $locationConditions[] = "location LIKE :location" . $index;
            }
            $conditions[] = "(" . implode(' OR ', $locationConditions) . ")";
        }
        
        // If no conditions, return empty result
        if (empty($conditions)) {
            return [];
        }
        
        $sql = "SELECT * FROM guide_listings 
                WHERE " . implode(' AND ', $conditions) . "
                ORDER BY avg_rating DESC";

        $this->db->query($sql);
        
        // Bind query parameters if provided
        if (!empty($query)) {
            $param = '%' . $query . '%';
            $this->db->bind(':query1', $param);
            $this->db->bind(':query2', $param);
            $this->db->bind(':query3', $param);
            $this->db->bind(':query4', $param);
        }
        
        // Bind location parameters if provided
        if (!empty($location)) {
            $locationNames = $this->mapLocationCode($location);
            foreach ($locationNames as $index => $locName) {
                $this->db->bind(':location' . $index, '%' . $locName . '%');
            }
        }

        return $this->db->resultSet();
    }

    public function updateBookingStatus($bookingId, $status)
    {
        $this->db->query("UPDATE bookings SET status = :status, updated_at = NOW() WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $bookingId);
        return $this->db->execute();
    }

    public function getBookingById($bookingId, $guideId)
    {
        $this->db->query("SELECT b.*, u.name as client_name, u.email as client_email
                        FROM bookings b
                        JOIN users u ON b.user_id = u.id
                        WHERE b.id = :id AND b.guide_id = :guide_id");
        $this->db->bind(':id', $bookingId);
        $this->db->bind(':guide_id', $guideId);
        return $this->db->single();
    }

    public function getBookingsByGuideId($guideId)
    {
        $this->db->query("SELECT * FROM bookings WHERE guide_id = :guide_id");
        $this->db->bind(':guide_id', $guideId);
        return $this->db->resultSet();
    }

    // Cập nhật rating trung bình của guide
    public function updateAverageRating($guide_id)
    {
        // Lấy thống kê rating từ tất cả các review
        $this->db->query('SELECT 
            AVG(rating) as avg_rating,
            COUNT(*) as total_reviews
            FROM guide_reviews 
            WHERE guide_id = :guide_id');

        $this->db->bind(':guide_id', $guide_id);
        $result = $this->db->single();

        // Cập nhật guide_profiles
        $this->db->query('UPDATE guide_profiles 
            SET avg_rating = :avg_rating,
                total_reviews = :total_reviews
            WHERE id = :guide_id');

        $this->db->bind(':guide_id', $guide_id);
        $this->db->bind(':avg_rating', $result->avg_rating ?? 0);
        $this->db->bind(':total_reviews', $result->total_reviews ?? 0);

        return $this->db->execute();
    }
    public function getGuideById($id)
    {
        // First try to get the guide by guide_profiles.id
        $this->db->query('
            SELECT 
                g.id as guide_id, 
                g.*, 
                u.id AS user_id, 
                u.name, 
                u.email, 
                u.status,
                u.profile_image,
                g.verified, 
                g.avg_rating, 
                g.total_reviews, 
                g.hourly_rate, 
                g.daily_rate, 
                g.available, 
                g.experience_years,
                g.bio,
                g.location
            FROM guide_profiles g
            JOIN users u ON u.id = g.user_id
            WHERE g.id = :id
        ');
        $this->db->bind(':id', $id);
        $guide = $this->db->single();

        // If not found, try to get by user_id
        if (!$guide) {
            $this->db->query('
                SELECT 
                    g.id as guide_id,
                    g.*, 
                    u.id AS user_id, 
                    u.name, 
                    u.email, 
                    u.status,
                    u.profile_image,
                    g.verified, 
                    g.avg_rating, 
                    g.total_reviews, 
                    g.hourly_rate, 
                    g.daily_rate, 
                    g.available, 
                    g.experience_years,
                    g.bio,
                    g.location
                FROM guide_profiles g
                JOIN users u ON u.id = g.user_id
                WHERE g.user_id = :user_id
            ');
            $this->db->bind(':user_id', $id);
            $guide = $this->db->single();
        }

        // If still no guide found, return false
        if (!$guide) {
            return false;
        }

        // Initialize default values for nullable fields
        $guide->avg_rating = $guide->avg_rating ?? 0;
        $guide->total_reviews = $guide->total_reviews ?? 0;
        $guide->hourly_rate = $guide->hourly_rate ?? 0;
        $guide->daily_rate = $guide->daily_rate ?? 0;
        $guide->experience_years = $guide->experience_years ?? 0;
        $guide->verified = $guide->verified ?? 0;
        $guide->available = $guide->available ?? 0;
        $guide->status = $guide->status ?? 'inactive';
        $guide->bio = $guide->bio ?? '';
        $guide->location = $guide->location ?? '';

        // Ensure guide_id is set
        if (!isset($guide->guide_id)) {
            $guide->guide_id = $guide->id;
        }

        // Fetch specialties
        $this->db->query('
            SELECT s.name 
            FROM guide_specialties gs
            JOIN specialties s ON gs.specialty_id = s.id
            WHERE gs.guide_id = :id
        ');
        $this->db->bind(':id', $guide->guide_id);
        $specialties = $this->db->resultSet();
        $guide->specialties = !empty($specialties) ? implode(', ', array_map(fn($s) => $s->name, $specialties)) : '';

        // Fetch all languages with fluency levels
        $this->db->query('
            SELECT l.name, gl.fluency_level
            FROM guide_languages gl
            JOIN languages l ON gl.language_id = l.id
            WHERE gl.guide_id = :id
        ');
        $this->db->bind(':id', $guide->guide_id);
        $languages = $this->db->resultSet();
        
        // Separate regular and fluent languages
        $allLanguages = [];
        $fluentLanguages = [];
        foreach ($languages as $lang) {
            $allLanguages[] = $lang->name;
            if (in_array(strtolower($lang->fluency_level), ['fluent','native'], true)) {
                $fluentLanguages[] = $lang->name;
            }
        }
        
        $guide->languages = implode(', ', $allLanguages);
        $guide->fluent_languages = implode(', ', $fluentLanguages);

        return $guide;
    }

    public function updateGuide($id, $data)
    {
        try {
            // First get the guide to get the user_id
            $this->db->query('SELECT user_id FROM guide_profiles WHERE id = :id');
            $this->db->bind(':id', $id);
            $guide = $this->db->single();
            
            if (!$guide) {
                error_log("Guide not found with ID: " . $id);
                return false;
            }

            // Convert verified to integer and ensure it's either 0 or 1
            $verified = isset($data['verified']) ? (int)$data['verified'] : 0;
            $verified = $verified ? 1 : 0;

            // Update guide_profiles with all fields
            $this->db->query('UPDATE guide_profiles 
                SET verified = :verified, 
                    avg_rating = :avg_rating, 
                    total_reviews = :total_reviews, 
                    hourly_rate = :hourly_rate, 
                    daily_rate = :daily_rate, 
                    available = :available, 
                    experience_years = :experience_years,
                    bio = :bio,
                    location = :location
                WHERE id = :id');
            
            $this->db->bind(':verified', $verified);
            $this->db->bind(':avg_rating', floatval($data['avg_rating'] ?? 0));
            $this->db->bind(':total_reviews', intval($data['total_reviews'] ?? 0));
            $this->db->bind(':hourly_rate', floatval($data['hourly_rate'] ?? 0));
            $this->db->bind(':daily_rate', floatval($data['daily_rate'] ?? 0));
            $this->db->bind(':available', isset($data['available']) ? (int)$data['available'] : 0);
            $this->db->bind(':experience_years', intval($data['experience_years'] ?? 0));
            $this->db->bind(':bio', $data['bio'] ?? '');
            $this->db->bind(':location', $data['location'] ?? '');
            $this->db->bind(':id', $id);
            
            if (!$this->db->execute()) {
                error_log("Failed to update guide_profiles for ID: " . $id);
                return false;
            }

            // Update user information
            $this->db->query('UPDATE users 
                SET status = :status,
                    name = :name,
                    email = :email
                WHERE id = :user_id');
            
            $this->db->bind(':status', $verified ? 'active' : 'inactive');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':user_id', $guide->user_id);
            
            if (!$this->db->execute()) {
                error_log("Failed to update user information for user_id: " . $guide->user_id);
                return false;
            }

            // Update specialties
            $this->db->query('DELETE FROM guide_specialties WHERE guide_id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();
            
            if (!empty($data['specialties'])) {
                $specialties = array_filter(array_map('trim', explode(',', $data['specialties'])));
                foreach ($specialties as $specialty) {
                    $this->db->query('SELECT id FROM specialties WHERE name = :name LIMIT 1');
                    $this->db->bind(':name', $specialty);
                    $specialtyRow = $this->db->single();
                    if ($specialtyRow) {
                        $this->db->query('INSERT INTO guide_specialties (guide_id, specialty_id) VALUES (:id, :specialty_id)');
                        $this->db->bind(':id', $id);
                        $this->db->bind(':specialty_id', $specialtyRow->id);
                        $this->db->execute();
                    }
                }
            }

            // Update languages
            $this->db->query('DELETE FROM guide_languages WHERE guide_id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();

            if (!empty($data['languages'])) {
                $languages = array_filter(array_map('trim', explode(',', $data['languages'])));
                $fluent_languages = !empty($data['fluent_languages']) ? 
                    array_map('strtolower', array_filter(array_map('trim', explode(',', $data['fluent_languages'])))) : [];

                foreach ($languages as $language) {
                    $this->db->query('SELECT id FROM languages WHERE name = :name LIMIT 1');
                    $this->db->bind(':name', $language);
                    $row = $this->db->single();
                    if ($row) {
                        $is_fluent = in_array(strtolower($language), $fluent_languages) ? 1 : 0;
                        $this->db->query('INSERT INTO guide_languages (guide_id, language_id, fluent) VALUES (:id, :language_id, :fluent)');
                        $this->db->bind(':id', $id);
                        $this->db->bind(':language_id', $row->id);
                        $this->db->bind(':fluent', $is_fluent);
                        $this->db->execute();
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            error_log("Error updating guide: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get guide profile by user ID
     * 
     * @param int $userId The user ID
     * @return object|boolean The guide profile or false
     */
    public function getGuideProfileByUserId($userId)
    {
        $this->db->query('
            SELECT g.*, u.name, u.email, u.profile_image, u.created_at
            FROM guide_profiles g
            JOIN users u ON g.user_id = u.id
            WHERE u.id = :user_id
        ');
        $this->db->bind(':user_id', $userId);

        return $this->db->single();
    }

    /**
     * Get guide statistics
     * 
     * @param int $guideId The guide ID
     * @return object The statistics
     */
    public function getGuideStats($guideId)
    {
        $stats = new stdClass();

        // Total bookings
        $this->db->query('SELECT COUNT(*) as count FROM bookings WHERE guide_id = :guide_id');
        $this->db->bind(':guide_id', $guideId);
        $result = $this->db->single();
        $stats->total_bookings = $result ? $result->count : 0;

        // Pending bookings
        $this->db->query('SELECT COUNT(*) as count FROM bookings WHERE guide_id = :guide_id AND status = "pending"');
        $this->db->bind(':guide_id', $guideId);
        $result = $this->db->single();
        $stats->pending_bookings = $result ? $result->count : 0;

        // Monthly revenue
        $this->db->query('
            SELECT SUM(total_price) as revenue 
            FROM bookings 
            WHERE guide_id = :guide_id 
            AND status = "completed"
            AND MONTH(booking_date) = MONTH(CURRENT_DATE())
            AND YEAR(booking_date) = YEAR(CURRENT_DATE())
        ');
        $this->db->bind(':guide_id', $guideId);
        $result = $this->db->single();
        $stats->monthly_revenue = $result ? $result->revenue : 0;

        return $stats;
    }

    /**
     * Get recent bookings for a guide
     * 
     * @param int $guideId The guide ID
     * @param int $limit The number of bookings to get
     * @return array The bookings
     */
    public function getRecentBookings($guideId, $limit = 5)
    {
        $this->db->query('
            SELECT b.*, u.name as client_name
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.guide_id = :guide_id
            ORDER BY b.created_at DESC
            LIMIT :limit
        ');
        $this->db->bind(':guide_id', $guideId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }
    public function setAvailability($guideId, $status)
    {
        $this->db->query('UPDATE guide_profiles SET available = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $guideId);
        return $this->db->execute();
    }
    public function getGuideByUserId($user_id)
    {
        $this->db->query('SELECT * FROM guide_profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }
    public function updateProfile($guideId, $data)
    {
        $this->db->query('UPDATE guide_profiles SET bio = :bio, hourly_rate = :hourly_rate, daily_rate = :daily_rate, location = :location WHERE id = :id');
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':hourly_rate', $data['hourly_rate']);
        $this->db->bind(':daily_rate', $data['daily_rate']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':id', $guideId);
        return $this->db->execute();
    }
    public function updateSpecialties($guideId, $specialtiesCommaSeparated)
    {
        // Remove all current specialties
        $this->db->query('DELETE FROM guide_specialties WHERE guide_id = :guide_id');
        $this->db->bind(':guide_id', $guideId);
        $this->db->execute();

        // Add new specialties
        $specialties = array_filter(array_map('trim', explode(',', $specialtiesCommaSeparated)));
        foreach ($specialties as $specialtyName) {
            // Find the specialty_id by name
            $this->db->query('SELECT id FROM specialties WHERE name = :name LIMIT 1');
            $this->db->bind(':name', $specialtyName);
            $row = $this->db->single();
            if ($row) {
                $specialty_id = $row->id;
                $this->db->query('INSERT INTO guide_specialties (guide_id, specialty_id) VALUES (:id, :specialty_id)');
                $this->db->bind(':id', $guideId);
                $this->db->bind(':specialty_id', $specialty_id);
                $this->db->execute();
            }
        }
    }
    public function updateLanguages($guideId, $languagesCommaSeparated)
    {
        // Remove all current languages
        $this->db->query('DELETE FROM guide_languages WHERE guide_id = :guide_id');
        $this->db->bind(':guide_id', $guideId);
        $this->db->execute();

        // Add new languages
        $languages = array_filter(array_map('trim', explode(',', $languagesCommaSeparated)));
        foreach ($languages as $languageName) {
            // Find the language_id by name
            $this->db->query('SELECT id FROM languages WHERE name = :name LIMIT 1');
            $this->db->bind(':name', $languageName);
            $row = $this->db->single();
            if ($row) {
                $language_id = $row->id;
                $this->db->query('INSERT INTO guide_languages (guide_id, language_id) VALUES (:guide_id, :language_id)');
                $this->db->bind(':guide_id', $guideId);
                $this->db->bind(':language_id', $language_id);
                $this->db->execute();
            }
        }
    }
}
