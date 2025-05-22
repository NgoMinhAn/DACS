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
    public function searchGuides($query)
    {
        // Use positional placeholders for each field
        $sql = "SELECT * FROM guide_listings 
                WHERE name LIKE ? 
                OR bio LIKE ? 
                OR location LIKE ? 
                OR specialties LIKE ?
                ORDER BY avg_rating DESC";

        $this->db->query($sql);
        $param = '%' . $query . '%';
        $this->db->bind(1, $param);
        $this->db->bind(2, $param);
        $this->db->bind(3, $param);
        $this->db->bind(4, $param);

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
        $this->db->query('
        SELECT g.id AS guide_id, u.id AS user_id, u.name, u.email, u.status, 
               g.verified, g.avg_rating, g.total_reviews, g.hourly_rate, g.daily_rate, 
               g.available, g.experience_years
        FROM users u
        JOIN guide_profiles g ON u.id = g.user_id
        WHERE g.id = :id
    ');
        $this->db->bind(':id', $id);
        $guide = $this->db->single();

        // Fetch specialties
        $this->db->query('
        SELECT s.name 
        FROM guide_specialties gs
        JOIN specialties s ON gs.specialty_id = s.id
        WHERE gs.guide_id = :id
    ');
        $this->db->bind(':id', $id);
        $specialties = $this->db->resultSet();
        $guide->specialties = implode(', ', array_map(fn($s) => $s->name, $specialties));

        // Fetch all languages
        $this->db->query('
        SELECT l.name 
        FROM guide_languages gl
        JOIN languages l ON gl.language_id = l.id
        WHERE gl.guide_id = :id
    ');
        $this->db->bind(':id', $id);
        $languages = $this->db->resultSet();
        $guide->languages = implode(', ', array_map(fn($l) => $l->name, $languages));

        // Fetch fluent languages
        $this->db->query('
        SELECT l.name 
        FROM guide_languages gl
        JOIN languages l ON gl.language_id = l.id
        WHERE gl.guide_id = :id AND gl.fluent = 1
    ');
        $this->db->bind(':id', $id);
        $fluentLangs = $this->db->resultSet();
        $guide->fluent_languages = implode(', ', array_map(fn($l) => $l->name, $fluentLangs));

        return $guide;
    }

    public function updateGuide($id, $data)
    {
        // Update guide_profiles as before (without specialties/languages)
        $this->db->query('UPDATE guide_profiles 
    SET verified = :verified, avg_rating = :avg_rating, total_reviews = :total_reviews, 
        hourly_rate = :hourly_rate, daily_rate = :daily_rate, available = :available, 
        experience_years = :experience_years
    WHERE id = :id');
        $this->db->bind(':verified', $data['verified']);
        $this->db->bind(':avg_rating', $data['avg_rating']);
        $this->db->bind(':total_reviews', $data['total_reviews']);
        $this->db->bind(':hourly_rate', $data['hourly_rate']);
        $this->db->bind(':daily_rate', $data['daily_rate']);
        $this->db->bind(':available', $data['available']);
        $this->db->bind(':experience_years', $data['experience_years'] ?? 0);
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Update specialties
        $this->db->query('DELETE FROM guide_specialties WHERE guide_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        $specialties = array_filter(array_map('trim', explode(',', $data['specialties'] ?? '')));
        foreach ($specialties as $specialty) {
            // Find the specialty_id by name
            $this->db->query('SELECT id FROM specialties WHERE name = :name LIMIT 1');
            $this->db->bind(':name', $specialty);
            $specialtyRow = $this->db->single();
            if ($specialtyRow) {
                $specialty_id = $specialtyRow->id;
                $this->db->query('INSERT INTO guide_specialties (guide_id, specialty_id) VALUES (:id, :specialty_id)');
                $this->db->bind(':id', $id);
                $this->db->bind(':specialty_id', $specialty_id);
                $this->db->execute();
            }
        }

        // After deleting old guide_languages:
        $this->db->query('DELETE FROM guide_languages WHERE guide_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        $languages = array_filter(array_map('trim', explode(',', $data['languages'] ?? '')));
        $fluent_languages = array_map('strtolower', array_filter(array_map('trim', explode(',', $data['fluent_languages'] ?? ''))));

        foreach ($languages as $language) {
            // Find the language_id by name
            $this->db->query('SELECT id FROM languages WHERE name = :name LIMIT 1');
            $this->db->bind(':name', $language);
            $languageRow = $this->db->single();
            if ($languageRow) {
                $language_id = $languageRow->id;
                $is_fluent = in_array(strtolower($language), $fluent_languages) ? 1 : 0;
                $this->db->query('INSERT INTO guide_languages (guide_id, language_id, fluent) VALUES (:id, :language_id, :fluent)');
                $this->db->bind(':id', $id);
                $this->db->bind(':language_id', $language_id);
                $this->db->bind(':fluent', $is_fluent);
                $this->db->execute();
            }
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
        $this->db->query('UPDATE guide_profiles SET bio = :bio, hourly_rate = :hourly_rate, daily_rate = :daily_rate WHERE id = :id');
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':hourly_rate', $data['hourly_rate']);
        $this->db->bind(':daily_rate', $data['daily_rate']);
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
                $this->db->query('INSERT INTO guide_specialties (guide_id, specialty_id) VALUES (:guide_id, :specialty_id)');
                $this->db->bind(':guide_id', $guideId);
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
