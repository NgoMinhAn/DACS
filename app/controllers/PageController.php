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