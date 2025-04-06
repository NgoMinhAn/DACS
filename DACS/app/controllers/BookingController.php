<?php
/**
 * Booking Controller
 * Handles booking related functionality
 */
class BookingController {
    /**
     * Redirect to specified path
     */
    private function redirect($path) {
        header("Location: $path");
        exit;
    }
    
    /**
     * Display booking form
     */
    public function create($guideId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to book a tour';
            $this->redirect('/login');
            return;
        }
        
        // Get guide details
        $guideModel = new Guide();
        $guide = $guideModel->findById($guideId);
        
        if (!$guide) {
            $_SESSION['error'] = 'Guide not found';
            $this->redirect('/guides');
            return;
        }
        
        // Load view
        include VIEWS_PATH . '/bookings/create.php';
    }
    
    /**
     * Process booking form
     */
    public function store() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to book a tour';
            $this->redirect('/login');
            return;
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/guides');
            return;
        }
        
        // Validate form data
        $userId = $_SESSION['user_id'];
        $guideId = $_POST['guide_id'] ?? 0;
        $date = $_POST['date'] ?? '';
        $startTime = $_POST['start_time'] ?? '';
        $hours = $_POST['hours'] ?? 0;
        $people = $_POST['people'] ?? 1;
        $notes = $_POST['notes'] ?? '';
        
        // Validate inputs
        $errors = [];
        if (empty($guideId) || !is_numeric($guideId)) $errors[] = 'Invalid guide';
        if (empty($date)) $errors[] = 'Date is required';
        if (empty($startTime)) $errors[] = 'Start time is required';
        if (empty($hours) || !is_numeric($hours) || $hours < 1) $errors[] = 'Tour duration must be at least 1 hour';
        if (empty($people) || !is_numeric($people) || $people < 1) $errors[] = 'Number of people must be at least 1';
        
        // Check if date is in the future
        $bookingDate = new DateTime("$date $startTime");
        $now = new DateTime();
        if ($bookingDate <= $now) {
            $errors[] = 'Booking must be for a future date and time';
        }
        
        // Check if guide exists
        $guideModel = new Guide();
        $guide = $guideModel->findById($guideId);
        if (!$guide) {
            $errors[] = 'Guide not found';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'guide_id' => $guideId,
                'date' => $date,
                'start_time' => $startTime,
                'hours' => $hours,
                'people' => $people,
                'notes' => $notes
            ];
            $this->redirect("/bookings/create/$guideId");
            return;
        }
        
        // Calculate end time
        $endTime = (new DateTime($startTime))->modify("+$hours hours")->format('H:i:s');
        
        // Check guide availability
        $bookingModel = new Booking();
        if (!$bookingModel->isGuideAvailable($guideId, $date, $startTime, $endTime)) {
            $_SESSION['error'] = 'The guide is not available at the selected time';
            $_SESSION['old_input'] = [
                'guide_id' => $guideId,
                'date' => $date,
                'start_time' => $startTime,
                'hours' => $hours,
                'people' => $people,
                'notes' => $notes
            ];
            $this->redirect("/bookings/create/$guideId");
            return;
        }
        
        // Calculate total amount
        $hourlyRate = $guide['hourly_rate'];
        $totalAmount = $hourlyRate * $hours * $people;
        
        // Create booking
        $bookingId = $bookingModel->create([
            'user_id' => $userId,
            'guide_id' => $guideId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'hours' => $hours,
            'people' => $people,
            'notes' => $notes,
            'hourly_rate' => $hourlyRate,
            'total_amount' => $totalAmount
        ]);
        
        if (!$bookingId) {
            $_SESSION['error'] = 'An error occurred while creating your booking';
            $this->redirect("/bookings/create/$guideId");
            return;
        }
        
        $_SESSION['success'] = 'Booking created successfully! Proceed to payment to confirm your booking.';
        $this->redirect("/bookings/payment/$bookingId");
    }
    
    /**
     * Display payment form for booking
     */
    public function payment($bookingId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to make a payment';
            $this->redirect('/login');
            return;
        }
        
        // Get booking details
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $_SESSION['error'] = 'Booking not found';
            $this->redirect('/profile');
            return;
        }
        
        // Check if booking belongs to logged-in user
        if ($booking['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'You do not have permission to access this booking';
            $this->redirect('/profile');
            return;
        }
        
        // Get guide details
        $guideModel = new Guide();
        $guide = $guideModel->findById($booking['guide_id']);
        
        // Load view
        include VIEWS_PATH . '/bookings/payment.php';
    }
    
    /**
     * Process payment form
     */
    public function processPayment() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to make a payment';
            $this->redirect('/login');
            return;
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
            return;
        }
        
        // Validate form data
        $bookingId = $_POST['booking_id'] ?? 0;
        $paymentMethod = $_POST['payment_method'] ?? '';
        
        // Validate inputs
        $errors = [];
        if (empty($bookingId) || !is_numeric($bookingId)) $errors[] = 'Invalid booking';
        if (empty($paymentMethod)) $errors[] = 'Payment method is required';
        
        // Get booking details
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $errors[] = 'Booking not found';
        } else if ($booking['user_id'] != $_SESSION['user_id']) {
            $errors[] = 'You do not have permission to access this booking';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect("/bookings/payment/$bookingId");
            return;
        }
        
        // In a real application, we would process the payment with a payment gateway here
        // For demonstration purposes, we'll just update the booking status
        
        // Update booking status to 'confirmed' and payment status to 'paid'
        $bookingModel->updateStatus($bookingId, 'confirmed');
        $bookingModel->updatePaymentStatus($bookingId, 'paid');
        
        // Create payment record
        $paymentModel = new Payment();
        $paymentId = $paymentModel->create([
            'booking_id' => $bookingId,
            'amount' => $booking['total_amount'],
            'payment_method' => $paymentMethod,
            'status' => 'completed'
        ]);
        
        $_SESSION['success'] = 'Payment successful! Your booking is now confirmed.';
        $this->redirect("/bookings/confirm/$bookingId");
    }
    
    /**
     * Display booking confirmation
     */
    public function confirm($bookingId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view booking confirmation';
            $this->redirect('/login');
            return;
        }
        
        // Get booking details
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $_SESSION['error'] = 'Booking not found';
            $this->redirect('/profile');
            return;
        }
        
        // Check if booking belongs to logged-in user
        if ($booking['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'You do not have permission to access this booking';
            $this->redirect('/profile');
            return;
        }
        
        // Get guide details
        $guideModel = new Guide();
        $guide = $guideModel->findById($booking['guide_id']);
        
        // Load view
        include VIEWS_PATH . '/bookings/confirm.php';
    }
    
    /**
     * Display user's bookings
     */
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your bookings';
            $this->redirect('/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $bookingModel = new Booking();
        $page = $_GET['page'] ?? 1;
        $bookings = $bookingModel->getByUserId($userId, $page, 10);
        $totalBookings = $bookingModel->countByUserId($userId);
        $totalPages = ceil($totalBookings / 10);
        
        // Load view
        include VIEWS_PATH . '/bookings/index.php';
    }
    
    /**
     * Display booking details
     */
    public function show($bookingId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view booking details';
            $this->redirect('/login');
            return;
        }
        
        // Get booking details
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $_SESSION['error'] = 'Booking not found';
            $this->redirect('/bookings');
            return;
        }
        
        // Check if booking belongs to logged-in user or the guide
        $userId = $_SESSION['user_id'];
        if ($booking['user_id'] != $userId && $booking['guide_user_id'] != $userId) {
            $_SESSION['error'] = 'You do not have permission to view this booking';
            $this->redirect('/bookings');
            return;
        }
        
        // Get guide details
        $guideModel = new Guide();
        $guide = $guideModel->findById($booking['guide_id']);
        
        // Load view
        include VIEWS_PATH . '/bookings/show.php';
    }
    
    /**
     * Cancel booking
     */
    public function cancel($bookingId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to cancel a booking';
            $this->redirect('/login');
            return;
        }
        
        // Get booking details
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $_SESSION['error'] = 'Booking not found';
            $this->redirect('/bookings');
            return;
        }
        
        // Check if booking belongs to logged-in user
        $userId = $_SESSION['user_id'];
        if ($booking['user_id'] != $userId) {
            $_SESSION['error'] = 'You do not have permission to cancel this booking';
            $this->redirect('/bookings');
            return;
        }
        
        // Check if booking is already completed or cancelled
        if ($booking['status'] === 'completed' || $booking['status'] === 'cancelled') {
            $_SESSION['error'] = 'This booking cannot be cancelled';
            $this->redirect("/bookings/show/$bookingId");
            return;
        }
        
        // Calculate cancellation fee based on how close to the booking date
        $bookingDate = new DateTime($booking['date'] . ' ' . $booking['start_time']);
        $now = new DateTime();
        $daysUntilBooking = $now->diff($bookingDate)->days;
        
        $refundAmount = $booking['total_amount'];
        $cancellationFee = 0;
        
        // If less than 48 hours, 50% fee
        if ($daysUntilBooking < 2) {
            $cancellationFee = $booking['total_amount'] * 0.5;
            $refundAmount = $booking['total_amount'] - $cancellationFee;
        } 
        // If less than 7 days, 25% fee
        else if ($daysUntilBooking < 7) {
            $cancellationFee = $booking['total_amount'] * 0.25;
            $refundAmount = $booking['total_amount'] - $cancellationFee;
        }
        
        // Update booking status
        $bookingModel->updateStatus($bookingId, 'cancelled');
        
        // If payment was made, process refund
        if ($booking['payment_status'] === 'paid') {
            // In a real application, we would process the refund with a payment gateway here
            // For demonstration purposes, we'll just create a refund record
            $paymentModel = new Payment();
            $paymentModel->createRefund([
                'booking_id' => $bookingId,
                'amount' => $refundAmount,
                'cancellation_fee' => $cancellationFee
            ]);
        }
        
        $_SESSION['success'] = 'Booking cancelled successfully. ' . 
                              ($booking['payment_status'] === 'paid' ? 
                                "A refund of $" . number_format($refundAmount, 2) . " will be processed." : 
                                "");
        $this->redirect('/bookings');
    }
} 