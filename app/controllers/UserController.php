<?php
/**
 * User Controller
 * Handles user-related functionality including bookings
 */
class UserController {
    private $userModel;
    private $bookingModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('account/login');
        }
        
        // Load models
        $this->userModel = new UserModel();
        $this->bookingModel = new BookingModel();
    }
    
    /**
     * Display user's bookings
     */
    public function bookings() {
        // Get user's bookings
        $bookings = $this->bookingModel->getUserBookings($_SESSION['user_id']);
        
        $data = [
            'title' => 'My Bookings',
            'bookings' => $bookings
        ];
        
        $this->loadView('user/bookings', $data);
    }
    
    /**
     * Load view helper
     */
    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEW_PATH . '/shares/header.php';
        require_once VIEW_PATH . '/' . $view . '.php';
        require_once VIEW_PATH . '/shares/footer.php';
    }
} 