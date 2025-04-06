<?php
/**
 * Review Controller
 * Handles review related functionality
 */
class ReviewController {
    /**
     * Redirect to specified path
     */
    private function redirect($path) {
        header("Location: $path");
        exit;
    }
    
    /**
     * Display review form
     */
    public function create($bookingId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to leave a review';
            $this->redirect('/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Get booking details
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $_SESSION['error'] = 'Booking not found';
            $this->redirect('/bookings');
            return;
        }
        
        // Check if booking belongs to logged-in user
        if ($booking['user_id'] != $userId) {
            $_SESSION['error'] = 'You can only review your own bookings';
            $this->redirect('/bookings');
            return;
        }
        
        // Check if booking is completed
        if ($booking['status'] !== 'completed') {
            $_SESSION['error'] = 'You can only review completed bookings';
            $this->redirect("/bookings/show/$bookingId");
            return;
        }
        
        // Check if review already exists
        $reviewModel = new Review();
        $existingReview = $reviewModel->findByBookingId($bookingId);
        
        if ($existingReview) {
            $_SESSION['error'] = 'You have already reviewed this booking';
            $this->redirect("/bookings/show/$bookingId");
            return;
        }
        
        // Get guide details
        $guideModel = new Guide();
        $guide = $guideModel->findById($booking['guide_id']);
        
        // Load view
        include VIEWS_PATH . '/reviews/create.php';
    }
    
    /**
     * Process review form
     */
    public function store() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to leave a review';
            $this->redirect('/login');
            return;
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bookings');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Validate form data
        $bookingId = $_POST['booking_id'] ?? 0;
        $guideId = $_POST['guide_id'] ?? 0;
        $rating = $_POST['rating'] ?? 0;
        $comment = $_POST['comment'] ?? '';
        
        // Validate inputs
        $errors = [];
        if (empty($bookingId) || !is_numeric($bookingId)) $errors[] = 'Invalid booking';
        if (empty($guideId) || !is_numeric($guideId)) $errors[] = 'Invalid guide';
        if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) $errors[] = 'Rating must be between 1 and 5';
        if (empty($comment)) $errors[] = 'Review comment is required';
        
        // Get booking details
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $errors[] = 'Booking not found';
        } else if ($booking['user_id'] != $userId) {
            $errors[] = 'You can only review your own bookings';
        } else if ($booking['status'] !== 'completed') {
            $errors[] = 'You can only review completed bookings';
        }
        
        // Check if review already exists
        $reviewModel = new Review();
        $existingReview = $reviewModel->findByBookingId($bookingId);
        
        if ($existingReview) {
            $errors[] = 'You have already reviewed this booking';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'rating' => $rating,
                'comment' => $comment
            ];
            $this->redirect("/reviews/create/$bookingId");
            return;
        }
        
        // Create review
        $reviewId = $reviewModel->create([
            'user_id' => $userId,
            'guide_id' => $guideId,
            'booking_id' => $bookingId,
            'rating' => $rating,
            'comment' => $comment
        ]);
        
        if (!$reviewId) {
            $_SESSION['error'] = 'An error occurred while creating your review';
            $this->redirect("/reviews/create/$bookingId");
            return;
        }
        
        // Update guide's average rating
        $guideModel = new Guide();
        $guideModel->updateAverageRating($guideId);
        
        $_SESSION['success'] = 'Review submitted successfully. Thank you for your feedback!';
        $this->redirect("/bookings/show/$bookingId");
    }
    
    /**
     * Display form to edit review
     */
    public function edit($reviewId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to edit a review';
            $this->redirect('/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Get review details
        $reviewModel = new Review();
        $review = $reviewModel->findById($reviewId);
        
        if (!$review) {
            $_SESSION['error'] = 'Review not found';
            $this->redirect('/bookings');
            return;
        }
        
        // Check if review belongs to logged-in user
        if ($review['user_id'] != $userId) {
            $_SESSION['error'] = 'You can only edit your own reviews';
            $this->redirect('/bookings');
            return;
        }
        
        // Get guide and booking details
        $guideModel = new Guide();
        $guide = $guideModel->findById($review['guide_id']);
        
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($review['booking_id']);
        
        // Load view
        include VIEWS_PATH . '/reviews/edit.php';
    }
    
    /**
     * Update review
     */
    public function update() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to update a review';
            $this->redirect('/login');
            return;
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bookings');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Validate form data
        $reviewId = $_POST['review_id'] ?? 0;
        $rating = $_POST['rating'] ?? 0;
        $comment = $_POST['comment'] ?? '';
        
        // Validate inputs
        $errors = [];
        if (empty($reviewId) || !is_numeric($reviewId)) $errors[] = 'Invalid review';
        if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) $errors[] = 'Rating must be between 1 and 5';
        if (empty($comment)) $errors[] = 'Review comment is required';
        
        // Get review details
        $reviewModel = new Review();
        $review = $reviewModel->findById($reviewId);
        
        if (!$review) {
            $errors[] = 'Review not found';
        } else if ($review['user_id'] != $userId) {
            $errors[] = 'You can only edit your own reviews';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'rating' => $rating,
                'comment' => $comment
            ];
            $this->redirect("/reviews/edit/$reviewId");
            return;
        }
        
        // Update review
        $success = $reviewModel->update($reviewId, [
            'rating' => $rating,
            'comment' => $comment,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if (!$success) {
            $_SESSION['error'] = 'An error occurred while updating your review';
            $this->redirect("/reviews/edit/$reviewId");
            return;
        }
        
        // Update guide's average rating
        $guideModel = new Guide();
        $guideModel->updateAverageRating($review['guide_id']);
        
        $_SESSION['success'] = 'Review updated successfully';
        $this->redirect("/bookings/show/{$review['booking_id']}");
    }
    
    /**
     * Delete review
     */
    public function delete($reviewId) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to delete a review';
            $this->redirect('/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Get review details
        $reviewModel = new Review();
        $review = $reviewModel->findById($reviewId);
        
        if (!$review) {
            $_SESSION['error'] = 'Review not found';
            $this->redirect('/bookings');
            return;
        }
        
        // Check if review belongs to logged-in user
        if ($review['user_id'] != $userId) {
            $_SESSION['error'] = 'You can only delete your own reviews';
            $this->redirect('/bookings');
            return;
        }
        
        // Delete review
        $success = $reviewModel->delete($reviewId);
        
        if (!$success) {
            $_SESSION['error'] = 'An error occurred while deleting your review';
            $this->redirect("/bookings/show/{$review['booking_id']}");
            return;
        }
        
        // Update guide's average rating
        $guideModel = new Guide();
        $guideModel->updateAverageRating($review['guide_id']);
        
        $_SESSION['success'] = 'Review deleted successfully';
        $this->redirect("/bookings/show/{$review['booking_id']}");
    }
    
    /**
     * Display reviews for a guide
     */
    public function showGuideReviews($guideId) {
        // Get guide details
        $guideModel = new Guide();
        $guide = $guideModel->findById($guideId);
        
        if (!$guide) {
            $_SESSION['error'] = 'Guide not found';
            $this->redirect('/guides');
            return;
        }
        
        // Get reviews
        $reviewModel = new Review();
        $page = $_GET['page'] ?? 1;
        $reviews = $reviewModel->getByGuideId($guideId, $page, 10);
        $totalReviews = $reviewModel->countByGuideId($guideId);
        $totalPages = ceil($totalReviews / 10);
        
        // Load view
        include VIEWS_PATH . '/reviews/guide_reviews.php';
    }
    
    /**
     * Display reviews by a user
     */
    public function showUserReviews($userId = null) {
        // If no user ID provided, use logged-in user
        if (!$userId && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        } else if (!$userId) {
            $_SESSION['error'] = 'User ID is required';
            $this->redirect('/');
            return;
        }
        
        // Get user details
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            $this->redirect('/');
            return;
        }
        
        // Get reviews
        $reviewModel = new Review();
        $page = $_GET['page'] ?? 1;
        $reviews = $reviewModel->getByUserId($userId, $page, 10);
        $totalReviews = $reviewModel->countByUserId($userId);
        $totalPages = ceil($totalReviews / 10);
        
        // Load view
        include VIEWS_PATH . '/reviews/user_reviews.php';
    }
} 