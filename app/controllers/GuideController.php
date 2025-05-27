<?php
/**
 * Guide Controller
 * Handles all guide dashboard and profile functionality
 */
class GuideController
{
    private $userModel;
    private $guideModel;
    private $bookingModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Check if user is logged in and is a guide
        if (!isLoggedIn() || $_SESSION['user_type'] !== 'guide') {
            redirect('account/login');
        }

        // Load models
        $this->userModel = new UserModel();
        $this->guideModel = new GuideModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Guide dashboard
     */
    public function dashboard()
    {
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
    private function getGuideProfile($userId)
    {
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
    private function getGuideSpecialties($guideId)
    {
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
    private function getGuideLanguages($guideId)
    {
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
    private function getGuideStats($guideId)
    {
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
    private function getRecentBookings($guideId, $limit = 5)
    {
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
    private function getRecentReviews($guideId, $limit = 3)
    {
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
    private function getApprovedReviewCount($guideId)
    {
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
    public function toggleAvailability()
    {
        if (!isLoggedIn() || !isset($_SESSION['user_id'])) {
            redirect('account/login');
        }

        $guideModel = new GuideModel();
        $guide = $guideModel->getGuideByUserId($_SESSION['user_id']);
        if (!$guide) {
            die('Guide not found');
        }

        $newStatus = $guide->available ? 0 : 1;
        $guideModel->setAvailability($guide->id, $newStatus);

        redirect('guide/dashboard');
    }
    /**
     * Accept a booking
     * 
     * @param int $id The booking ID
     */
    public function booking($id)
    {
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

    public function acceptBooking($id)
    {
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

    public function declineBooking($id)
    {
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
    public function chat($bookingId)
    {
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
     * Display all reviews for the guide
     */
    public function reviewsList()
    {
        // Get guide information
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        $guide = $this->getGuideProfile($user->id);

        if (!$guide) {
            flash('guide_message', 'Guide profile not found.', 'alert alert-danger');
            redirect('guide/dashboard');
            return;
        }

        // Get all reviews for the guide
        $reviews = $this->guideModel->getGuideReviews($guide->id);

        // Get accurate review count
        $reviewCount = $this->getApprovedReviewCount($guide->id);

        $data = [
            'title' => 'My Reviews',
            'guide' => $guide,
            'reviews' => $reviews,
            'total_reviews' => $reviewCount
        ];

        $this->loadView('tourGuides/GuideDashboard/reviews', $data);
    }

    public function editProfile()
    {
        $userId = $_SESSION['user_id'];
        $guide = $this->getGuideProfile($userId);

        if (!$guide) {
            flash('guide_message', 'Profile updated successfully!', 'alert alert-success');
            redirect('guide/dashboard'); // <-- change this line
            return;
        }

        // Get current specialties and languages as comma-separated strings
        $specialties = $this->getGuideSpecialties($guide->id);
        $languages = $this->getGuideLanguages($guide->id);

        $specialties_str = implode(', ', array_map(fn($s) => $s->name, $specialties));
        $languages_str = implode(', ', array_map(fn($l) => $l->name, $languages));

        // Lấy tất cả specialties để render checkbox
        $all_specialties = $this->guideModel->getAllSpecialties();
        // Tạo mảng specialties đã chọn
        $selected_specialties = array_map(fn($s) => $s->name, $specialties);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $name = trim($_POST['name'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $specialties_input = isset($_POST['specialties']) ? $_POST['specialties'] : [];
            $languages_input = trim($_POST['languages'] ?? '');
            $hourly_rate = floatval($_POST['hourly_rate'] ?? 0);
            $daily_rate = floatval($_POST['daily_rate'] ?? 0);

            // Update user table (name)
            $this->userModel->updateName($userId, $name);

            // Update guide_profiles table
            $this->guideModel->updateProfile($guide->id, [
                'bio' => $bio,
                'hourly_rate' => $hourly_rate,
                'daily_rate' => $daily_rate,
                'location' => $location
            ]);

            // Update specialties
            $this->guideModel->updateSpecialties($guide->id, implode(',', $specialties_input));

            // Update languages
            $this->guideModel->updateLanguages($guide->id, $languages_input);

            flash('guide_message', 'Profile updated successfully!', 'alert alert-success');
            redirect('guide/dashboard');
            return;
        }

        // Prepare data for the view
        $data = [
            'guide' => $guide,
            'specialties' => $specialties_str,
            'languages' => $languages_str,
            'all_specialties' => $all_specialties,
            'selected_specialties' => $selected_specialties
        ];

        $this->loadView('tourGuides/edit-profile', $data);
    }

    public function updateRates()
    {
        if (!isLoggedIn() || !isset($_SESSION['user_id'])) {
            redirect('account/login');
        }

        $guideModel = new GuideModel();
        $guide = $guideModel->getGuideByUserId($_SESSION['user_id']);
        if (!$guide) {
            flash('guide_message', 'Guide not found.', 'alert alert-danger');
            redirect('guide/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hourly = floatval($_POST['hourly_rate'] ?? 0);
            $daily = floatval($_POST['daily_rate'] ?? 0);
            $guideModel->updateProfile($guide->id, [
                'bio' => $guide->bio,
                'hourly_rate' => $hourly,
                'daily_rate' => $daily,
                'location' => $guide->location ?? ''
            ]);
            flash('guide_message', 'Rates updated successfully!', 'alert alert-success');
        }

        redirect('guide/dashboard');
    }
    public function calendar()
    {
        // Example: Load bookings and availability for the calendar
        $userId = $_SESSION['user_id'];
        $guide = $this->getGuideProfile($userId);

        // Prepare $calendar_events as needed
        $calendar_events = []; // Fill with your events

        $this->loadView('tourGuides/calendar', [
            'guide' => $guide,
            'calendar_events' => $calendar_events
        ]);
    }

    public function updateBio()
    {
        if (!isLoggedIn() || !isset($_SESSION['user_id'])) {
            redirect('account/login');
        }

        $guideModel = new GuideModel();
        $guide = $guideModel->getGuideByUserId($_SESSION['user_id']);
        if (!$guide) {
            flash('guide_message', 'Guide not found.', 'alert alert-danger');
            redirect('guide/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bio = trim($_POST['bio'] ?? '');
            // Lấy location từ form, nếu không có thì dùng location hiện tại
            $location = isset($_POST['location']) ? trim($_POST['location']) : ($guide->location ?? '');
            $guideModel->updateProfile($guide->id, [
                'bio' => $bio,
                'hourly_rate' => $guide->hourly_rate,
                'daily_rate' => $guide->daily_rate,
                'location' => $location
            ]);
            flash('guide_message', 'Bio updated successfully!', 'alert alert-success');
        }

        redirect('guide/dashboard');
    }
    public function accountSettings()
    {
        $userId = $_SESSION['user_id'];
        $guide = $this->getGuideProfile($userId);
        $this->loadView('tourGuides/accoutSettings/settings', ['guide' => $guide]);
    }

    public function profileSettings()
    {
        $userId = $_SESSION['user_id'];
        $guide = $this->getGuideProfile($userId);
        $profile_message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $location = trim($_POST['location'] ?? '');

            // Update name in users table
            $this->userModel->updateName($userId, $name);

            // Handle profile image upload
            $profile_image = $guide->profile_image;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $filename = 'guide_' . $userId . '_' . time() . '.' . $ext;
                $uploadDir = dirname(__DIR__, 2) . '/assets/images/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $target = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
                    $profile_image = $filename;
                    // Update profile_image in users table
                    $this->userModel->updateProfileImage($userId, $profile_image);
                }
            }

            // Update guide_profiles table
            $this->guideModel->updateProfile($guide->id, [
                'bio' => $bio,
                'hourly_rate' => $guide->hourly_rate,
                'daily_rate' => $guide->daily_rate,
                'location' => $location
            ]);

            $profile_message = "Profile updated successfully!";
            // Reload updated guide info
            $guide = $this->getGuideProfile($userId);
        }

        $this->loadView('tourGuides/accoutSettings/profile', [
            'guide' => $guide,
            'profile_message' => $profile_message
        ]);
    }

    public function passwordSettings()
    {
        $userId = $_SESSION['user_id'];
        $guide = $this->getGuideProfile($userId);
        $password_message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Verify current password
            if (!$this->userModel->verifyPassword($userId, $current_password)) {
                $password_message = "Current password is incorrect.";
            } elseif ($new_password !== $confirm_password) {
                $password_message = "New passwords do not match.";
            } elseif (strlen($new_password) < 6) {
                $password_message = "New password must be at least 6 characters.";
            } else {
                $this->userModel->updatePassword($userId, $new_password);
                $password_message = "Password updated successfully!";
            }
        }

        $this->loadView('tourGuides/accoutSettings/password', [
            'guide' => $guide,
            'password_message' => $password_message
        ]);
    }

    /**
     * Load a view with the header and footer
     * 
     * @param string $view The view to load
     * @param array $data The data to pass to the view
     */
    private function loadView($view, $data = [])
    {
        // Extract data variables into the current symbol table
        extract($data);

        // Load header
        require_once VIEW_PATH . '/shares/header.php';

        // Load the view
        require_once VIEW_PATH . '/' . $view . '.php';

        // Load footer
        require_once VIEW_PATH . '/shares/footer.php';
    }

    /**
     * Display all bookings for the guide
     */
    public function bookingsList()
    {
        // Get guide information
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        $guide = $this->getGuideProfile($user->id);

        if (!$guide) {
            flash('guide_message', 'Guide profile not found. Please contact an administrator.', 'alert alert-danger');
            redirect('');
            return;
        }

        // Get all bookings for this guide
        $db = new Database();
        $db->query('
            SELECT b.*, u.name as client_name, u.email as client_email
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            WHERE b.guide_id = :guide_id
            ORDER BY b.booking_date DESC, b.start_time DESC
        ');
        $db->bind(':guide_id', $guide->id);
        $bookings = $db->resultSet();

        $data = [
            'title' => 'All Bookings',
            'bookings' => $bookings
        ];

        $this->loadView('tourGuides/bookings', $data);
    }

    /**
     * Hiển thị danh sách contact requests gửi đến guide này
     */
    public function contactRequests()
    {
        $userId = $_SESSION['user_id'];
        $guide = $this->getGuideProfile($userId);
        if (!$guide) {
            flash('guide_message', 'Guide profile not found.', 'alert alert-danger');
            redirect('guide/dashboard');
            return;
        }
        $db = new Database();
        $db->query('SELECT * FROM contact_requests WHERE guide_id = :guide_id ORDER BY id DESC');
        $db->bind(':guide_id', $guide->id);
        $contact_requests = $db->resultSet();
        $this->loadView('tourGuides/contactRequests', [
            'guide' => $guide,
            'contact_requests' => $contact_requests
        ]);
    }
}