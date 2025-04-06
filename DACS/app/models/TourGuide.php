<?php

class TourGuide {
    public $id;
    public $user_id;
    public $speciality;
    public $experience;
    public $languages;
    public $hourly_rate;
    public $bio;
    public $rating;
    public $review_count;
    public $created_at;
    public $updated_at;
    
    // Constructor
    public function __construct($id, $user_id, $speciality, $experience, $languages, $hourly_rate, $bio, $rating = 0, $review_count = 0, $created_at = null, $updated_at = null) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->speciality = $speciality;
        $this->experience = $experience;
        $this->languages = is_array($languages) ? $languages : json_decode($languages, true);
        $this->hourly_rate = $hourly_rate;
        $this->bio = $bio;
        $this->rating = $rating;
        $this->review_count = $review_count;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
        $this->updated_at = $updated_at ?? date('Y-m-d H:i:s');
    }
    
    // Create new tour guide profile
    public static function create($user_id, $speciality, $experience, $languages, $hourly_rate, $bio) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Check if user already has a guide profile
        $stmt = $conn->prepare("SELECT * FROM tour_guides WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception("User already has a tour guide profile");
        }
        
        // Insert new tour guide
        $stmt = $conn->prepare("INSERT INTO tour_guides (user_id, speciality, experience, languages, hourly_rate, bio, rating, review_count, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $now = date('Y-m-d H:i:s');
        $languages_json = is_array($languages) ? json_encode($languages) : $languages;
        $result = $stmt->execute([$user_id, $speciality, $experience, $languages_json, $hourly_rate, $bio, 0, 0, $now, $now]);
        
        if (!$result) {
            throw new Exception("Failed to create tour guide profile");
        }
        
        // Get the newly created tour guide ID
        $id = $conn->lastInsertId();
        
        // Return new tour guide object
        return new TourGuide($id, $user_id, $speciality, $experience, $languages, $hourly_rate, $bio, 0, 0, $now, $now);
    }
    
    // Find tour guide by ID
    public static function findById($id) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("SELECT * FROM tour_guides WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() === 0) {
            return null;
        }
        
        $guide = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return new TourGuide(
            $guide['id'],
            $guide['user_id'],
            $guide['speciality'],
            $guide['experience'],
            $guide['languages'],
            $guide['hourly_rate'],
            $guide['bio'],
            $guide['rating'],
            $guide['review_count'],
            $guide['created_at'],
            $guide['updated_at']
        );
    }
    
    // Find tour guide by user ID
    public static function findByUserId($user_id) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("SELECT * FROM tour_guides WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() === 0) {
            return null;
        }
        
        $guide = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return new TourGuide(
            $guide['id'],
            $guide['user_id'],
            $guide['speciality'],
            $guide['experience'],
            $guide['languages'],
            $guide['hourly_rate'],
            $guide['bio'],
            $guide['rating'],
            $guide['review_count'],
            $guide['created_at'],
            $guide['updated_at']
        );
    }
    
    // Get all tour guides
    public static function getAll() {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("SELECT tg.*, u.name FROM tour_guides tg JOIN users u ON tg.user_id = u.id ORDER BY tg.rating DESC");
        $stmt->execute();
        
        $guides = [];
        while ($guide = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $guides[] = new TourGuide(
                $guide['id'],
                $guide['user_id'],
                $guide['speciality'],
                $guide['experience'],
                $guide['languages'],
                $guide['hourly_rate'],
                $guide['bio'],
                $guide['rating'],
                $guide['review_count'],
                $guide['created_at'],
                $guide['updated_at']
            );
        }
        
        return $guides;
    }
    
    // Update tour guide profile
    public function update($speciality, $experience, $languages, $hourly_rate, $bio) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Update tour guide data
        $stmt = $conn->prepare("UPDATE tour_guides SET speciality = ?, experience = ?, languages = ?, hourly_rate = ?, bio = ?, updated_at = ? WHERE id = ?");
        $now = date('Y-m-d H:i:s');
        $languages_json = is_array($languages) ? json_encode($languages) : $languages;
        $result = $stmt->execute([$speciality, $experience, $languages_json, $hourly_rate, $bio, $now, $this->id]);
        
        if (!$result) {
            throw new Exception("Failed to update tour guide profile");
        }
        
        // Update object properties
        $this->speciality = $speciality;
        $this->experience = $experience;
        $this->languages = is_array($languages) ? $languages : json_decode($languages, true);
        $this->hourly_rate = $hourly_rate;
        $this->bio = $bio;
        $this->updated_at = $now;
        
        return true;
    }
    
    // Update guide's availability
    public function updateAvailability($available_days, $available_hours) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Delete existing availability
        $stmt = $conn->prepare("DELETE FROM guide_availability WHERE guide_id = ?");
        $stmt->execute([$this->id]);
        
        // Insert new availability
        $stmt = $conn->prepare("INSERT INTO guide_availability (guide_id, day, start_time, end_time) VALUES (?, ?, ?, ?)");
        
        foreach ($available_days as $day) {
            foreach ($available_hours as $hours) {
                $start_time = $hours['start'] ?? '';
                $end_time = $hours['end'] ?? '';
                
                if (empty($start_time) || empty($end_time)) {
                    continue;
                }
                
                $stmt->execute([$this->id, $day, $start_time, $end_time]);
            }
        }
        
        return true;
    }
    
    // Get guide's availability
    public function getAvailability() {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $stmt = $conn->prepare("SELECT * FROM guide_availability WHERE guide_id = ? ORDER BY day, start_time");
        $stmt->execute([$this->id]);
        
        $availability = [];
        while ($slot = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $availability[] = [
                'day' => $slot['day'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time']
            ];
        }
        
        return $availability;
    }
    
    // Check if guide is available at a specific time
    public function isAvailable($date, $start_time, $end_time) {
        // Get day of week
        $day = date('l', strtotime($date));
        
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Check guide's availability
        $stmt = $conn->prepare("SELECT * FROM guide_availability WHERE guide_id = ? AND day = ? AND start_time <= ? AND end_time >= ?");
        $stmt->execute([$this->id, $day, $start_time, $end_time]);
        
        if ($stmt->rowCount() === 0) {
            return false;
        }
        
        // Check for existing bookings
        $stmt = $conn->prepare("
            SELECT * FROM bookings 
            WHERE guide_id = ? 
            AND date = ? 
            AND status IN ('pending', 'approved', 'confirmed') 
            AND (
                (start_time <= ? AND end_time > ?) OR
                (start_time < ? AND end_time >= ?) OR
                (start_time >= ? AND end_time <= ?)
            )
        ");
        $stmt->execute([$this->id, $date, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time]);
        
        return $stmt->rowCount() === 0;
    }
    
    // Update guide's rating based on reviews
    public function updateRating() {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Get average rating from reviews
        $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as count FROM reviews WHERE guide_id = ?");
        $stmt->execute([$this->id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $avg_rating = $result['avg_rating'] ?? 0;
        $count = $result['count'] ?? 0;
        
        // Update guide's rating
        $stmt = $conn->prepare("UPDATE tour_guides SET rating = ?, review_count = ?, updated_at = ? WHERE id = ?");
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$avg_rating, $count, $now, $this->id]);
        
        // Update object properties
        $this->rating = $avg_rating;
        $this->review_count = $count;
        $this->updated_at = $now;
        
        return true;
    }
    
    // Search for guides based on criteria
    public static function search($speciality = '', $language = '', $date = '') {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        $query = "SELECT tg.*, u.name FROM tour_guides tg JOIN users u ON tg.user_id = u.id WHERE 1=1";
        $params = [];
        
        if (!empty($speciality)) {
            $query .= " AND tg.speciality LIKE ?";
            $params[] = "%$speciality%";
        }
        
        if (!empty($language)) {
            $query .= " AND tg.languages LIKE ?";
            $params[] = "%$language%";
        }
        
        if (!empty($date)) {
            $day = date('l', strtotime($date));
            $query .= " AND tg.id IN (SELECT guide_id FROM guide_availability WHERE day = ?)";
            $params[] = $day;
        }
        
        $query .= " ORDER BY tg.rating DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        
        $guides = [];
        while ($guide = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $guides[] = new TourGuide(
                $guide['id'],
                $guide['user_id'],
                $guide['speciality'],
                $guide['experience'],
                $guide['languages'],
                $guide['hourly_rate'],
                $guide['bio'],
                $guide['rating'],
                $guide['review_count'],
                $guide['created_at'],
                $guide['updated_at']
            );
        }
        
        return $guides;
    }
    
    // Get user object associated with this guide
    public function getUser() {
        return User::findById($this->user_id);
    }
} 