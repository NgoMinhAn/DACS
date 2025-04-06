<?php
/**
 * Home Controller
 * Handles primary site pages
 */
class HomeController {
    /**
     * Redirect to specified path
     */
    private function redirect($path) {
        header("Location: $path");
        exit;
    }
    
    /**
     * Display homepage - fixed to avoid nested includes
     */
    public function index() {
        $featuredGuides = [];
        $latestReviews = [];
        
        try {
            // Get featured guides (with highest ratings)
            $guideModel = new Guide();
            $featuredGuides = $guideModel->getFeatured(4);
        } catch (PDOException $e) {
            // Log the error but don't crash the page
            error_log("Error fetching featured guides: " . $e->getMessage());
        }
        
        try {
            // Get latest reviews
            $reviewModel = new Review();
            $latestReviews = $reviewModel->getLatest(3);
        } catch (PDOException $e) {
            // Log the error but don't crash the page
            error_log("Error fetching latest reviews: " . $e->getMessage());
        }
        
        // Direct includes - no nesting
        include VIEWS_PATH . '/shares/header.php';
        include VIEWS_PATH . '/home/index.php';
        include VIEWS_PATH . '/shares/footer.php';
    }
    
    /**
     * Display about page
     */
    public function about() {
        include VIEWS_PATH . '/shares/header.php';
        include VIEWS_PATH . '/home/about.php';
        include VIEWS_PATH . '/shares/footer.php';
    }
    
    /**
     * Display contact page
     */
    public function contact() {
        include VIEWS_PATH . '/shares/header.php';
        include VIEWS_PATH . '/home/contact.php';
        include VIEWS_PATH . '/shares/footer.php';
    }
    
    /**
     * Process contact form
     */
    public function contactPost() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contact');
            return;
        }
        
        // Validate form data
        $name = $_POST['name'] ?? '';
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        
        // Validate inputs
        $errors = [];
        if (empty($name)) $errors[] = 'Name is required';
        if (!$email) $errors[] = 'Valid email is required';
        if (empty($subject)) $errors[] = 'Subject is required';
        if (empty($message)) $errors[] = 'Message is required';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ];
            $this->redirect('/contact');
            return;
        }
        
        // In a real application, send email here
        // For demonstration, just store in database
        
        // Create success message
        $_SESSION['success'] = 'Your message has been sent. We will contact you shortly!';
        $this->redirect('/contact');
    }
    
    /**
     * Display terms page
     */
    public function terms() {
        include VIEWS_PATH . '/shares/header.php';
        include VIEWS_PATH . '/home/terms.php';
        include VIEWS_PATH . '/shares/footer.php';
    }
    
    /**
     * Display privacy page
     */
    public function privacy() {
        include VIEWS_PATH . '/shares/header.php';
        include VIEWS_PATH . '/home/privacy.php';
        include VIEWS_PATH . '/shares/footer.php';
    }
    
    /**
     * Display cookies page
     */
    public function cookies() {
        include VIEWS_PATH . '/shares/header.php';
        include VIEWS_PATH . '/home/cookies.php';
        include VIEWS_PATH . '/shares/footer.php';
    }
    
    /**
     * Display FAQ page
     */
    public function faq() {
        include VIEWS_PATH . '/shares/header.php';
        include VIEWS_PATH . '/home/faq.php';
        include VIEWS_PATH . '/shares/footer.php';
    }
}