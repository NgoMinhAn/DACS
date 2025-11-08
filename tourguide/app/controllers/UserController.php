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
     * Display user's bookings with pagination
     */
    public function bookings() {
        // Pagination settings
        $itemsPerPage = 4;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max(1, $currentPage); // Ensure page is at least 1
        
        // Calculate offset
        $offset = ($currentPage - 1) * $itemsPerPage;
        
        // Get total count of bookings
        $totalBookings = $this->bookingModel->getUserBookingsCount($_SESSION['user_id']);
        
        // Get paginated bookings
        $bookings = $this->bookingModel->getUserBookings($_SESSION['user_id'], $itemsPerPage, $offset);
        
        // Calculate total pages
        $totalPages = ceil($totalBookings / $itemsPerPage);
        
        // Get all bookings for stats (only if needed for stats display)
        // We get all bookings to calculate statistics accurately
        $allBookings = $this->bookingModel->getUserBookings($_SESSION['user_id']);
        
        $data = [
            'title' => 'My Bookings',
            'bookings' => $bookings,
            'allBookings' => $allBookings, // For statistics
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalBookings' => $totalBookings,
            'itemsPerPage' => $itemsPerPage
        ];
        
        $this->loadView('user/bookings', $data);
    }
    public function dashboard() {
    $userId = $_SESSION['user_id'];
    $bookingModel = new BookingModel();
    $bookings = $bookingModel->getUserBookings($userId);
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

public function bookingDetail($id) {
    $bookingModel = new BookingModel();
    $booking = $bookingModel->getBookingById($id, $_SESSION['user_id']);
    if (!$booking) {
        die('Booking not found');
    }
    $this->loadView('user/bookingDetail', ['booking' => $booking]);
}

    /**
     * View invoice details (AJAX)
     */
    public function invoice($id) {
        $booking = $this->bookingModel->getBookingById($id, $_SESSION['user_id']);
        
        if (!$booking) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Booking not found']);
            exit;
        }

        // Get user info
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        
        // Render invoice HTML
        ob_start();
        include VIEW_PATH . '/user/invoice_template.php';
        $html = ob_get_clean();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'html' => $html]);
        exit;
    }

    /**
     * Print invoice
     */
    public function printInvoice($id) {
        $booking = $this->bookingModel->getBookingById($id, $_SESSION['user_id']);
        
        if (!$booking) {
            die('Booking not found');
        }

        // Get user info
        $user = $this->userModel->findUserById($_SESSION['user_id']);
        
        $data = [
            'title' => 'Invoice #' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
            'booking' => $booking,
            'user' => $user
        ];
        
        $this->loadView('user/invoice_print', $data);
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