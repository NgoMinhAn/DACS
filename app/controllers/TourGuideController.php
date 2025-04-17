<?php
/**
 * Tour Guide Controller
 * Handles all tour guide related functionality
 */
class TourGuideController {
    private $guideModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Load the guide model
        $this->guideModel = new GuideModel();
    }
    
    /**
     * Index method - default landing page
     * Displays featured guides rather than tours
     */
    public function index() {
        // Get featured guides from the database
        $featuredGuides = $this->guideModel->getFeaturedGuides(4);
        
        // Get all specialties for category sections
        $specialties = $this->guideModel->getAllSpecialties();
        
        // Data to be passed to the view
        $data = [
            'title' => 'Connect with Expert Tour Guides',
            'subtitle' => 'Find the perfect local guide for your next adventure',
            'featured_guides' => $featuredGuides,
            'guide_categories' => $specialties
        ];
        
        // Load view
        $this->loadView('tourGuides/index', $data);
    }
    
    /**
     * Browse all guides
     */
    public function browse() {
        // Get filter parameters if set
        $filters = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['language']) && !empty($_GET['language'])) {
                $filters['language'] = sanitize($_GET['language']);
            }
            
            if (isset($_GET['specialty']) && !empty($_GET['specialty'])) {
                $filters['specialty'] = sanitize($_GET['specialty']);
            }
            
            if (isset($_GET['price_range']) && !empty($_GET['price_range'])) {
                $filters['price_range'] = sanitize($_GET['price_range']);
            }
        }
        
        // Get guides from database with filters
        $guides = $this->guideModel->getAllGuides($filters);
        
        // Get all languages and specialties for filter options
        $languages = $this->guideModel->getAllLanguages();
        $specialties = $this->guideModel->getAllSpecialties();
        
        // Data to be passed to the view
        $data = [
            'title' => 'Browse All Guides',
            'guides' => $guides,
            'languages' => $languages,
            'specialties' => $specialties,
            'current_filters' => $filters
        ];
        
        // Load view
        $this->loadView('tourGuides/browse', $data);
    }
    
    /**
     * View single guide profile
     * 
     * @param int $id The guide ID
     */
    public function profile($id = null) {
        if ($id === null) {
            redirect('tourGuide/browse');
        }
        
        // Get guide details from database
        $guide = $this->guideModel->getGuideById($id);
        
        // If guide not found, redirect to browse page
        if (!$guide) {
            flash('error_message', 'Guide not found', 'alert alert-danger');
            redirect('tourGuide/browse');
        }
        
        // Get guide reviews and additional details
        $reviews = $this->guideModel->getGuideReviews($guide->guide_id);
        $specialties = $this->guideModel->getGuideSpecialties($guide->guide_id);
        $languages = $this->guideModel->getGuideLanguages($guide->guide_id);
        
        // Data to be passed to the view
        $data = [
            'title' => $guide->name . ' - Tour Guide',
            'guide' => $guide,
            'reviews' => $reviews,
            'specialties' => $specialties,
            'languages' => $languages
        ];
        
        // Load view
        $this->loadView('tourGuides/profile', $data);
    }
    
    /**
     * Contact a guide
     * 
     * @param int $id The guide ID
     */
    public function contact($id = null) {
        if ($id === null) {
            redirect('tourGuide/browse');
        }
        
        // Get guide details (to be implemented with actual model)
        // $guide = $this->guideModel->getGuideById($id);
        
        // For now, use dummy data
        $guide = [
            'id' => $id,
            'name' => 'Sample Guide',
            'email' => 'guide' . $id . '@example.com'
        ];
        
        // If form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form data (to be implemented)
            flash('contact_message', 'Your message has been sent to the guide.');
            redirect('tourGuide/profile/' . $id);
        }
        
        // Data to be passed to the view
        $data = [
            'title' => 'Contact ' . $guide['name'],
            'guide' => $guide
        ];
        
        // Load view
        $this->loadView('tourGuides/contact', $data);
    }
    
    /**
     * Load a view with the header and footer
     * 
     * @param string $view The view to load
     * @param array $data The data to pass to the view
     */
    private function loadView($view, $data = []) {
        // Extract data variables into the current symbol table
        extract($data);
        
        // Load header
        $headerFile = VIEW_PATH . '/shares/header.php';
        if (file_exists($headerFile)) {
            require_once $headerFile;
        } else {
            echo "<p>Error: Header file not found at {$headerFile}</p>";
        }
        
        // Load the view
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<p>Error: View file not found at {$viewFile}</p>";
        }
        
        // Load footer
        $footerFile = VIEW_PATH . '/shares/footer.php';
        if (file_exists($footerFile)) {
            require_once $footerFile;
        } else {
            echo "<p>Error: Footer file not found at {$footerFile}</p>";
        }
    }
} 