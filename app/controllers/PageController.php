<?php
/**
 * Page Controller
 * Handles static pages like About Us, Terms, etc.
 */
class PageController {
    /**
     * Constructor
     */
    public function __construct() {
        // No model needed for static pages
    }
    
    /**
     * Index method - redirect to home
     */
    public function index() {
        redirect('');
    }
    
    /**
     * About method - displays the about us page
     */
    public function about() {
        $data = [
            'title' => 'About Us'
        ];
        
        $this->loadView('pages/about', $data);
    }
    
    /**
     * Contact method - displays the contact page
     */
    public function contact() {
        $data = [
            'title' => 'Contact Us'
        ];
        
        $this->loadView('pages/contact', $data);
    }
    
    /**
     * Submit contact form
     */
    public function submit_contact() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('contact');
        }
        
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, [
            'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
            'subject' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'message' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
        ]);
        
        // Validate inputs
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);
        $privacy = isset($_POST['privacy']) ? true : false;
        
        // Check for empty values
        if (empty($name) || empty($email) || empty($subject) || empty($message) || !$privacy) {
            flash('contact_message', 'Please fill in all fields and accept the privacy policy', 'alert alert-danger');
            redirect('contact');
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('contact_message', 'Please enter a valid email address', 'alert alert-danger');
            redirect('contact');
        }
        
        // In a real application, you would:
        // 1. Store the message in a database
        // 2. Send an email notification
        // 3. Possibly set up an admin notification
        
        // For demonstration purposes, we'll just show a success message
        flash('contact_message', 'Thank you for your message. We will get back to you soon!', 'alert alert-success');
        redirect('contact');
    }
    
    /**
     * Terms method - displays the terms of service
     */
    public function terms() {
        $data = [
            'title' => 'Terms of Service'
        ];
        
        $this->loadView('pages/terms', $data);
    }
    
    /**
     * Privacy method - displays the privacy policy
     */
    public function privacy() {
        $data = [
            'title' => 'Privacy Policy'
        ];
        
        $this->loadView('pages/privacy', $data);
    }
    
    /**
     * Careers method - displays the careers page
     */
    public function careers() {
        $data = [
            'title' => 'Careers'
        ];
        
        $this->loadView('pages/careers', $data);
    }
    
    /**
     * Load view helper
     * 
     * @param string $view The view file
     * @param array $data The data to pass to the view
     */
    private function loadView($view, $data = []) {
        // Extract the data to make it available in the view
        extract($data);
        
        // Include header
        require_once VIEW_PATH . '/shares/header.php';
        
        // Include main view
        require_once VIEW_PATH . '/' . $view . '.php';
        
        // Include footer
        require_once VIEW_PATH . '/shares/footer.php';
    }
} 