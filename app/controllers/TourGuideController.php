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
        // Load the guide model (to be implemented)
        // $this->guideModel = new GuideModel();
    }
    
    /**
     * Index method - default landing page
     * Displays featured guides rather than tours
     */
    public function index() {
        // Data to be passed to the view
        $data = [
            'title' => 'Connect with Expert Tour Guides',
            'subtitle' => 'Find the perfect local guide for your next adventure',
            'featured_guides' => [], // To be populated from database
            'guide_categories' => [] // To be populated from database
        ];
        
        // Load view
        $this->loadView('tourGuides/index', $data);
    }
    
    /**
     * Browse all guides
     */
    public function browse() {
        // Data to be passed to the view
        $data = [
            'title' => 'Browse All Guides',
            'guides' => [] // To be populated from database
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
        
        // Get guide details (to be implemented with actual model)
        // $guide = $this->guideModel->getGuideById($id);
        
        // For now, use dummy data
        $guide = [
            'id' => $id,
            'name' => 'Sample Guide',
            'bio' => 'Expert local guide with 10+ years of experience',
            'rating' => 4.8,
            'reviews' => 24,
            'specialties' => ['Historical Tours', 'Local Cuisine', 'Off the Beaten Path'],
            'languages' => ['English', 'Spanish', 'French'],
            'image' => 'default_guide.jpg',
            'hourly_rate' => 50,
            'available' => true
        ];
        
        // Data to be passed to the view
        $data = [
            'title' => $guide['name'] . ' - Tour Guide',
            'guide' => $guide
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
        require_once VIEW_PATH . '/shares/header.php';
        
        // Load the view
        require_once VIEW_PATH . '/' . $view . '.php';
        
        // Load footer
        require_once VIEW_PATH . '/shares/footer.php';
    }
} 