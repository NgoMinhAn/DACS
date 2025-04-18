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
        $this->loadView('tourGuides/home', $data);
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
        
        // Get the number of approved reviews
        $approvedReviewCount = $this->guideModel->getApprovedReviewCount($guide->guide_id);
        
        // Get guide reviews and additional details
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 5; // Show 5 reviews per page
        $offset = ($page - 1) * $perPage;
        
        $reviews = $this->guideModel->getGuideReviews($guide->guide_id, $perPage, $offset);
        $specialties = $this->guideModel->getGuideSpecialties($guide->guide_id);
        $languages = $this->guideModel->getGuideLanguages($guide->guide_id);
        
        // Calculate rating distribution
        $five_star_count = 0;
        $four_star_count = 0;
        $three_star_count = 0;
        $two_star_count = 0;
        $one_star_count = 0;
        
        // Count reviews by rating
        if (!empty($reviews)) {
            foreach ($reviews as $review) {
                switch ($review->rating) {
                    case 5:
                        $five_star_count++;
                        break;
                    case 4:
                        $four_star_count++;
                        break;
                    case 3:
                        $three_star_count++;
                        break;
                    case 2:
                        $two_star_count++;
                        break;
                    case 1:
                        $one_star_count++;
                        break;
                }
            }
        }
        
        // Calculate percentages for rating distribution based on all reviews
        $ratings_distribution = [
            5 => [
                'count' => $five_star_count,
                'percentage' => $approvedReviewCount > 0 ? ($five_star_count / $approvedReviewCount * 100) : 0
            ],
            4 => [
                'count' => $four_star_count,
                'percentage' => $approvedReviewCount > 0 ? ($four_star_count / $approvedReviewCount * 100) : 0
            ],
            3 => [
                'count' => $three_star_count,
                'percentage' => $approvedReviewCount > 0 ? ($three_star_count / $approvedReviewCount * 100) : 0
            ],
            2 => [
                'count' => $two_star_count,
                'percentage' => $approvedReviewCount > 0 ? ($two_star_count / $approvedReviewCount * 100) : 0
            ],
            1 => [
                'count' => $one_star_count,
                'percentage' => $approvedReviewCount > 0 ? ($one_star_count / $approvedReviewCount * 100) : 0
            ]
        ];
        
        // Setup pagination
        $totalPages = ceil($approvedReviewCount / $perPage);
        $pagination = (object) [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'per_page' => $perPage,
            'total_records' => $approvedReviewCount
        ];
        
        // Create a custom guide object with accurate review count
        $guideWithAccurateReviewCount = clone $guide;
        $guideWithAccurateReviewCount->total_reviews = $approvedReviewCount;
        
        // Data to be passed to the view
        $data = [
            'title' => $guide->name . ' - Tour Guide',
            'guide' => $guideWithAccurateReviewCount,
            'reviews' => $reviews,
            'specialties' => $specialties,
            'languages' => $languages,
            'ratings_distribution' => $ratings_distribution,
            'five_star_count' => $five_star_count,
            'four_star_count' => $four_star_count,
            'three_star_count' => $three_star_count,
            'two_star_count' => $two_star_count,
            'one_star_count' => $one_star_count,
            'pagination' => $pagination,
            'approved_review_count' => $approvedReviewCount
        ];
        
        // Load view - use the new profile view
        $this->loadView('tourGuides/Accounts/profile', $data);
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