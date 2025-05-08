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