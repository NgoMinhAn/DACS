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
    public function dashboard() {
    $userId = $_SESSION['user_id'];
    $bookingModel = new BookingModel();
    $bookings = $bookingModel->getBookingsByUserId($userId);
    $this->loadView('user/dashboard', ['bookings' => $bookings]);
    }
    public function chat($bookingId) {
    $userId = $_SESSION['user_id'];
    $bookingModel = new BookingModel();
    $booking = $bookingModel->getBookingById($bookingId, $userId); // Make sure this method exists and checks user_id

    if (!$booking) {
        $this->loadView('errors/404');
        return;
    }

    $messageModel = new MessageModel();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
        $messageModel->sendMessage($bookingId, $userId, $_POST['message']);
        redirect('user/chat/' . $bookingId);
    }
    $messages = $messageModel->getMessages($bookingId);

    $this->loadView('user/chat', [
        'booking' => $booking,
        'messages' => $messages,
        'currentUserId' => $userId
    ]);
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