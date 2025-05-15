<?php
/**
 * Guide Controller
 * Handles all guide dashboard and profile functionality
 */
class GuideController {
    private $userModel;
    private $guideModel;
    private $bookingModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Check if user is logged in and is a guide
        if (!isLoggedIn() || $_SESSION['user_type'] !== 'guide') {
            redirect('account/login');
        }
        
        // Load models
        $this->userModel = new UserModel();
        
        // These models would need to be created
        // For now we'll initialize them with null and handle this gracefully
        $this->guideModel = class_exists('GuideModel') ? new GuideModel() : null;
        $this->bookingModel = class_exists('BookingModel') ? new BookingModel() : null;
    }
    
    /**
     * Guide dashboard
     */
    public function dashboard() {
        // Get guide information
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        
        // Get guide profile
        $guide = $this->getGuideProfile($user->id);
        
        // If guide profile doesn't exist, show error
        if (!$guide) {
            flash('guide_message', 'Guide profile not found. Please contact an administrator.', 'alert alert-danger');
            redirect('');
            return;
        }
        
        // Get guide's specialties
        $specialties = $this->getGuideSpecialties($guide->id);
        
        // Get guide's languages
        $languages = $this->getGuideLanguages($guide->id);
        
        // Get statistics
        $stats = $this->getGuideStats($guide->id);
        
        // Get recent bookings
        $recent_bookings = $this->getRecentBookings($guide->id, 5);
        
        // Get recent reviews
        $recent_reviews = $this->getRecentReviews($guide->id, 3);
        
        // Get accurate review count
        $reviewCount = $this->getApprovedReviewCount($guide->id);
        
        // Update guide object with accurate review count
        $guide->total_reviews = $reviewCount;
        
        $data = [
            'title' => 'Guide Dashboard',
            'user' => $user,
            'guide' => $guide,
            'specialties' => $specialties ?? [],
            'languages' => $languages ?? [],
            'stats' => $stats,
            'recent_bookings' => $recent_bookings ?? [],
            'recent_reviews' => $recent_reviews ?? []
        ];
        
        $this->loadView('tourGuides/GuideDashboard/GuideDashboard', $data);
    }
    
    /**
     * Get guide profile
     * 
     * @param int $userId The user ID
     * @return object The guide profile
     */
    private function getGuideProfile($userId) {
        // If GuideModel exists and has the required method, use it
        if ($this->guideModel && method_exists($this->guideModel, 'getGuideProfileByUserId')) {
            return $this->guideModel->getGuideProfileByUserId($userId);
        }
        
        // Otherwise, use a database query directly
        $db = new Database();
        $db->query('
            SELECT g.*, u.name, u.email, u.profile_image, u.created_at
            FROM guide_profiles g
            JOIN users u ON g.user_id = u.id
            WHERE u.id = :user_id
        ');
        $db->bind(':user_id', $userId);
        
        return $db->single();
    }
    
    /**
     * Get guide's specialties
     * 
     * @param int $guideId The guide ID
     * @return array The specialties
     */
    private function getGuideSpecialties($guideId) {
        // If GuideModel exists and has the required method, use it
        if ($this->guideModel && method_exists($this->guideModel, 'getGuideSpecialties')) {
            return $this->guideModel->getGuideSpecialties($guideId);
        }
        
        // Otherwise, use a database query directly
        $db = new Database();
        $db->query('
            SELECT s.*
            FROM specialties s
            JOIN guide_specialties gs ON s.id = gs.specialty_id
            WHERE gs.guide_id = :guide_id
            ORDER BY s.name
        ');
        $db->bind(':guide_id', $guideId);
        
        return $db->resultSet();
    }
    
    /**
     * Get guide's languages
     * 
     * @param int $guideId The guide ID
     * @return array The languages
     */
    private function getGuideLanguages($guideId) {
        // If GuideModel exists and has the required method, use it
        if ($this->guideModel && method_exists($this->guideModel, 'getGuideLanguages')) {
            return $this->guideModel->getGuideLanguages($guideId);
        }
        
        // Otherwise, use a database query directly
        $db = new Database();
        $db->query('
            SELECT l.*, gl.fluency_level
            FROM languages l
            JOIN guide_languages gl ON l.id = gl.language_id
            WHERE gl.guide_id = :guide_id
            ORDER BY gl.fluency_level DESC, l.name
        ');
        $db->bind(':guide_id', $guideId);
        
        return $db->resultSet();
    }
    
    /**
     * Get guide statistics
     * 
     * @param int $guideId The guide ID
     * @return object The statistics
     */
    private function getGuideStats($guideId) {
        // If BookingModel exists and has the required method, use it
        if ($this->bookingModel && method_exists($this->bookingModel, 'getGuideStats')) {
            return $this->bookingModel->getGuideStats($guideId);
        }
        
        // Otherwise, create a dummy stats object
        // In a real application, this would query the database
        $stats = new stdClass();
        $stats->total_bookings = 0;
        $stats->pending_bookings = 0;
        $stats->monthly_revenue = 0;
        
        // Try to get real stats from database
        $db = new Database();
        
        // Total bookings
        $db->query('SELECT COUNT(*) as count FROM bookings WHERE guide_id = :guide_id');
        $db->bind(':guide_id', $guideId);
        $result = $db->single();
        if ($result) {
            $stats->total_bookings = $result->count;
        }
        
        // Pending bookings
        $db->query('SELECT COUNT(*) as count FROM bookings WHERE guide_id = :guide_id AND status = "pending"');
        $db->bind(':guide_id', $guideId);
        $result = $db->single();
        if ($result) {
            $stats->pending_bookings = $result->count;
        }
        
        // Monthly revenue
        $db->query('
            SELECT SUM(total_price) as revenue 
            FROM bookings 
            WHERE guide_id = :guide_id 
            AND MONTH(booking_date) = MONTH(CURRENT_DATE()) 
            AND YEAR(booking_date) = YEAR(CURRENT_DATE())
            AND (status = "completed" OR status = "confirmed")
        ');
        $db->bind(':guide_id', $guideId);
        $result = $db->single();
        if ($result && $result->revenue) {
            $stats->monthly_revenue = $result->revenue;
        }
        
        return $stats;
    }
    
    /**
     * Get recent bookings
     * 
     * @param int $guideId The guide ID
     * @param int $limit The number of bookings to get
     * @return array The bookings
     */
    private function getRecentBookings($guideId, $limit = 5) {
        // If BookingModel exists and has the required method, use it
        if ($this->bookingModel && method_exists($this->bookingModel, 'getRecentBookings')) {
            return $this->bookingModel->getRecentBookings($guideId, $limit);
        }
        
        // Otherwise, use a database query directly
        $db = new Database();
        $db->query('
            SELECT b.*, u.name as client_name
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.guide_id = :guide_id
            ORDER BY b.booking_date DESC, b.start_time DESC
            LIMIT :limit
        ');
        $db->bind(':guide_id', $guideId);
        $db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $db->resultSet();
    }
    
    /**
     * Get recent reviews
     * 
     * @param int $guideId The guide ID
     * @param int $limit The number of reviews to get
     * @return array The reviews
     */
    private function getRecentReviews($guideId, $limit = 3) {
        // Use a database query directly
        $db = new Database();
        $db->query('
            SELECT r.*, u.name as user_name
            FROM guide_reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.guide_id = :guide_id AND r.status = "approved"
            ORDER BY r.created_at DESC
            LIMIT :limit
        ');
        $db->bind(':guide_id', $guideId);
        $db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $db->resultSet();
    }
    
    /**
     * Get the number of approved reviews for a guide
     * 
     * @param int $guideId The guide ID
     * @return int The number of approved reviews
     */
    private function getApprovedReviewCount($guideId) {
        // If GuideModel exists and has the required method, use it
        if ($this->guideModel && method_exists($this->guideModel, 'getApprovedReviewCount')) {
            return $this->guideModel->getApprovedReviewCount($guideId);
        }
        
        // Otherwise, use a database query directly
        $db = new Database();
        $db->query('
            SELECT COUNT(*) as count 
            FROM guide_reviews 
            WHERE guide_id = :guide_id AND status = "approved"
        ');
        $db->bind(':guide_id', $guideId);
        
        $result = $db->single();
        return $result ? $result->count : 0;
    }
    
    /**
     * Toggle guide availability
     */
    public function toggleAvailability() {
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        $guide = $this->getGuideProfile($user->id);
        
        // Toggle availability
        $db = new Database();
        $db->query('UPDATE guide_profiles SET available = NOT available WHERE id = :guide_id');
        $db->bind(':guide_id', $guide->id);
        
        if ($db->execute()) {
            flash('guide_message', 'Your availability has been updated.', 'alert alert-success');
        } else {
            flash('guide_message', 'Could not update your availability.', 'alert alert-danger');
        }
        
        redirect('guide/dashboard');
    }
    /**
     * Accept a booking
     * 
     * @param int $id The booking ID
     */
    public function booking($id) {
    $userId = $_SESSION['user_id'];
    $guide = $this->getGuideProfile($userId);
    $guideId = $guide ? $guide->id : null;

    $booking = $this->guideModel->getBookingById($id, $guideId);
    if (!$booking) {
        $this->loadView('errors/404');
        return;
    }
    $this->loadView('tourGuides/bookingDetails', ['booking' => $booking]);
}

    public function acceptBooking($id) {
    $userId = $_SESSION['user_id'];
    $guide = $this->getGuideProfile($userId);
    $guideId = $guide ? $guide->id : null;

    // Only allow if this guide owns the booking
    $booking = $this->guideModel->getBookingById($id, $guideId);
    if ($booking && $booking->status === 'pending') {
        $this->guideModel->updateBookingStatus($id, 'accepted');
        flash('guide_message', 'Booking accepted!', 'alert alert-success');
    }
    redirect('guide/booking/' . $id);
}

public function declineBooking($id) {
    $userId = $_SESSION['user_id'];
    $guide = $this->getGuideProfile($userId);
    $guideId = $guide ? $guide->id : null;

    $booking = $this->guideModel->getBookingById($id, $guideId);
    if ($booking && $booking->status === 'pending') {
        $this->guideModel->updateBookingStatus($id, 'declined');
        flash('guide_message', 'Booking declined.', 'alert alert-warning');
    }
    redirect('guide/booking/' . $id);
}

    /**
     * Chat with client
     * 
     * @param int $id The booking ID
     */
public function chat($bookingId) {
    $userId = $_SESSION['user_id'];
    $guide = $this->getGuideProfile($userId);
    $guideId = $guide ? $guide->id : null;

    $booking = $this->guideModel->getBookingById($bookingId, $guideId);
    if (!$booking) {
        $this->loadView('errors/404');
        return;
    }

    $messageModel = new MessageModel();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
        $messageModel->sendMessage($bookingId, $userId, $_POST['message']);
        redirect('guide/chat/' . $bookingId);
    }
    $messages = $messageModel->getMessages($bookingId);

    $this->loadView('tourGuides/chat', [
        'booking' => $booking,
        'messages' => $messages,
        'currentUserId' => $userId
    ]);
}
    /**
     * Load a view with the header and footer
     * 
     * @param string $view The view to load
     * @param array $data The data to pass to the view
     */
    private function loadView($view, $data = []) {
        // Extract data variables into the current symbol table
        extract($data);
        
        // Load header
        require_once VIEW_PATH . '/shares/header.php';
        
        // Load the view
        require_once VIEW_PATH . '/' . $view . '.php';
        
        // Load footer
        require_once VIEW_PATH . '/shares/footer.php';
    }
} 