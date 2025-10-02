<?php
/**
 * Tour Guide Controller
 * Handles all tour guide related functionality
 */
class TourGuideController {
    private $guideModel;
    private $reviewModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Load the guide model
        $this->guideModel = new GuideModel();
        $this->reviewModel = new ReviewModel();
    }
    
    /**
     * Index method - default landing page
     * Displays featured guides rather than tours
     */
    public function index() {
        // Get top 4 guides ordered by rating
        $featuredGuides = $this->guideModel->getTopRatedGuides(4);
        
        // Update featured guides with accurate review counts
        if (!empty($featuredGuides)) {
            foreach ($featuredGuides as $guide) {
                // Get approved review count for each guide
                $approvedReviewCount = $this->guideModel->getApprovedReviewCount($guide->guide_id);
                
                // Update the guide object with accurate review count
                $guide->total_reviews = $approvedReviewCount;
            }
        }
        
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
        
        // Update each guide with accurate review count
        if (!empty($guides)) {
            foreach ($guides as $guide) {
                // Get approved review count for each guide
                $approvedReviewCount = $this->guideModel->getApprovedReviewCount($guide->guide_id);
                
                // Update the guide object with accurate review count
                $guide->total_reviews = $approvedReviewCount;
            }
        }
        
        // Get all languages and specialties for filter options
        $languages = $this->guideModel->getAllLanguages();
        $specialties = $this->guideModel->getAllSpecialties();
        
        // AI Recommendation logic with Gemini
        $recommendations = null;
        $ai_debug = '';
        if (isset($_SESSION['user_id'])) {
            require_once APP_PATH . '/helpers/gemini_helper.php';
            require_once MODEL_PATH . '/UserModel.php';
            $userModel = new UserModel();
            $user = $userModel->findUserById($_SESSION['user_id']);
            $userHobbies = [];
            if (!empty($user->hobbies)) {
                $userHobbies = explode(',', $user->hobbies);
            }
            $pastGuides = [];
            // Debug: check API key
            if (!defined('GEMINI_API_KEY') || !GEMINI_API_KEY) {
                $ai_debug = 'AI: Gemini API key is missing.';
            } elseif (empty($userHobbies)) {
                $ai_debug = 'AI: No hobbies set in your profile. Add hobbies in your account settings for recommendations.';
            } else {
                $recommendations = getGeminiRecommendations($userHobbies, $pastGuides, GEMINI_API_KEY);
                if (empty($recommendations)) {
                    $ai_debug = 'AI: No recommendations returned. There may be a problem with the Gemini API or your network connection.';
                }
            }
        } else {
            $ai_debug = 'AI: You must be logged in to see recommendations.';
        }

        // Data to be passed to the view
        $data = [
            'title' => 'Browse All Guides',
            'guides' => $guides,
            'languages' => $languages,
            'specialties' => $specialties,
            'current_filters' => $filters,
            'ai_recommendations' => $recommendations,
            'ai_debug' => $ai_debug
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
            'approved_review_count' => $approvedReviewCount,
            'requires_auth' => !isLoggedIn()
        ];
        
        // Load view - use the new profile view
        $this->loadView('tourGuides/Accounts/profile', $data);
    }
    
    /**
     * Category method - handles all category-related views
     * 
     * @param string $type The category type (city, adventure, cultural, food, categories)
     */
    public function category($type = null) {
        // If no type specified, redirect to browse page
        if ($type === null) {
            redirect('tourGuide/browse');
        }
        
        // Get guides by category/specialty
        $categoryGuides = [];
        $title = '';
        
        // Handle different category types
        switch ($type) {
            case 'city':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('City');
                $title = 'City Tour Guides';
                break;
            case 'adventure':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('Adventure');
                $title = 'Adventure Tour Guides';
                break;
            case 'cultural':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('Cultural');
                $title = 'Cultural Tour Guides';
                break;
            case 'food':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('Food');
                $title = 'Food & Cuisine Guides';
                break;
            case 'historical':
            case 'history':
                // Redirect to browse instead of showing historical tours
                redirect('tourGuide/browse');
                break;
            case 'categories':
                // Get all categories
                $guideCategories = $this->guideModel->getAllSpecialties();
                // Additional filtering directly in controller to ensure removal
                if (!empty($guideCategories)) {
                    $filteredCategories = [];
                    foreach ($guideCategories as $category) {
                        // Skip any historical or off-beaten path categories
                        if (!in_array(strtolower($category->name), ['historical', 'history', 'historical tours', 'off-beaten path', 'off the beaten path'])) {
                            $filteredCategories[] = $category;
                        }
                    }
                    $guideCategories = $filteredCategories;
                }
                $data = [
                    'title' => 'Tour Guide Categories',
                    'guide_categories' => $guideCategories
                ];
                $this->loadView('tourGuides/category/categories', $data);
                return;
            default:
                // For all other specialties, try to find guides with that specialty
                error_log("Processing category: " . $type);
                
                // Define a mapping from URL slugs to exact specialty names in the database
                $specialtyMap = [
                    // Exact URL-friendly versions of database names
                    'adventure' => 'Adventure',
                    'historical-tours' => 'Historical Tours',
                    'food-cuisine' => 'Food & Cuisine',
                    'nature-wildlife' => 'Nature & Wildlife',
                    'architecture' => 'Architecture',
                    'cultural-immersion' => 'Cultural Immersion',
                    'off-the-beaten-path' => 'Off the Beaten Path',
                    'city-tours' => 'City Tours',
                    
                    // Common variations and shortcuts
                    'historical' => 'Historical Tours',
                    'history' => 'Historical Tours',
                    'food' => 'Food & Cuisine',
                    'cuisine' => 'Food & Cuisine',
                    'nature' => 'Nature & Wildlife',
                    'wildlife' => 'Nature & Wildlife',
                    'cultural' => 'Cultural Immersion',
                    'culture' => 'Cultural Immersion',
                    'off-beaten-path' => 'Off the Beaten Path',
                    'city' => 'City Tours'
                ];
                
                error_log("Trying to map '" . $type . "' to a database specialty");
                
                // Check if we have a direct mapping for this slug
                if (isset($specialtyMap[$type])) {
                    $exactSpecialty = $specialtyMap[$type];
                    error_log("Found exact specialty mapping: $type -> $exactSpecialty");
                    $categoryGuides = $this->guideModel->getGuidesBySpecialty($exactSpecialty);
                } else {
                    // Try different capitalizations and formats
                    $categoryGuides = [];
                    
                    // Try direct match first
                    $categoryGuides = $this->guideModel->getGuidesBySpecialty($type);
                    
                    // If no results, try with first letter capitalized
                    if (empty($categoryGuides)) {
                        $specialty = ucfirst($type);
                        $categoryGuides = $this->guideModel->getGuidesBySpecialty($specialty);
                        error_log("Trying with ucfirst: " . $specialty);
                    }
                    
                    // If still no results, try replacing dashes with spaces
                    if (empty($categoryGuides)) {
                        $specialty = str_replace('-', ' ', $type);
                        $specialty = ucwords($specialty); // Capitalize first letter of each word
                        $categoryGuides = $this->guideModel->getGuidesBySpecialty($specialty);
                        error_log("Trying with dashes replaced by spaces: " . $specialty);
                    }
                    
                    // If still no results, try replacing dashes with ampersand
                    if (empty($categoryGuides)) {
                        $specialty = str_replace('-and-', ' & ', $type);
                        $specialty = str_replace('-', ' ', $specialty);
                        $specialty = ucwords($specialty);
                        $categoryGuides = $this->guideModel->getGuidesBySpecialty($specialty);
                        error_log("Trying with ampersand: " . $specialty);
                    }
                }
                
                // Use the last specialty name we tried
                if (empty($categoryGuides)) {
                    flash('error_message', 'No guides found for this specialty.', 'alert alert-info');
                    error_log("No guides found for any variation of: " . $type);
                    redirect('tourGuide/browse');
                }
                
                // For title display, use a readable version
                $displayTitle = isset($specialtyMap[$type]) ? $specialtyMap[$type] : ucwords(str_replace('-', ' ', $type));
                $title = $displayTitle . ' Tour Guides';
                error_log("Found guides for category: " . $type . ", title: " . $title);
                break;
        }
        
        // Get sorting options if set
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        
        // Apply sorting if specified
        if ($sort) {
            // Sort logic would go here
            // For example:
            if ($sort === 'rating') {
                // Sort by rating
            } elseif ($sort === 'price_low') {
                // Sort by price low to high
            }
        }
        
        // Data to be passed to the view
        $data = [
            'title' => $title,
            'category_guides' => $categoryGuides
        ];
        
        // Try to load type-specific view first, fall back to generic if not found
        $specificView = 'tourGuides/category/' . $type;
        $genericView = 'tourGuides/category/generic';
        
        if (file_exists(VIEW_PATH . '/' . $specificView . '.php')) {
            $this->loadView($specificView, $data);
        } else {
            // Create a generic view if not found
            $this->loadView($genericView, $data);
        }
    }
    
    /**
     * Contact a guide
     * 
     * @param int $id The guide ID
     */
    public function contact($id = null) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            // Store the intended destination in session
            $_SESSION['redirect_after_login'] = 'tourGuide/contact/' . $id;
            flash('login_message', 'Please login to contact the guide.', 'alert alert-info');
            redirect('account/login');
        }

        if ($id === null) {
            redirect('tourGuide/browse');
        }
        
        // Get guide details from database
        $guide = $this->guideModel->getGuideById($id);
        if (!$guide) {
            flash('contact_message', 'Guide not found.', 'alert alert-danger');
            redirect('tourGuide/browse');
        }
        
        // If form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            // Save to contact_requests table
            $db = new Database();
            $db->query('INSERT INTO contact_requests (guide_id, name, email, message) VALUES (:guide_id, :name, :email, :message)');
            $db->bind(':guide_id', $guide->guide_id);
            $db->bind(':name', $name);
            $db->bind(':email', $email);
            $db->bind(':message', $message);
            $db->execute();
            
            flash('contact_message', 'Your message has been sent to the guide.');
            redirect('tourGuide/profile/' . $id);
        }
        
        // Data to be passed to the view
        $data = [
            'title' => 'Contact ' . $guide->name,
            'guide' => [
                'id' => $guide->guide_id,
                'name' => $guide->name,
                'email' => $guide->email
            ]
        ];
        
        $this->loadView('tourGuides/contact', $data);
    }

    /**
     * Show booking confirmation page
     */
    public function confirmBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guide_id = $_POST['guide_id'] ?? '';
            $booking_type = $_POST['booking_type'] ?? '';
            $hours = $_POST['hours'] ?? 0;

            // Get guide details to calculate price
            $guide = $this->guideModel->getGuideById($guide_id);
            
            // Calculate total amount
            $total_amount = 0;
            if ($guide) {
                if ($booking_type === 'hourly') {
                    $total_amount = $guide->hourly_rate * $hours;
                } else {
                    $total_amount = $guide->daily_rate;
                }
            }

            // Store booking info in session
            $_SESSION['pending_booking'] = [
                'guide_id' => $guide_id,
                'booking_date' => $_POST['booking_date'] ?? '',
                'booking_type' => $booking_type,
                'start_time' => $_POST['start_time'] ?? '',
                'hours' => $hours,
                'number_of_people' => $_POST['number_of_people'] ?? '',
                'meeting_location' => $_POST['meeting_location'] ?? '',
                'special_requests' => $_POST['special_requests'] ?? '',
                'total_amount' => $total_amount
            ];

            $data = [
                'guide_id' => $guide_id,
                'booking_date' => $_POST['booking_date'] ?? '',
                'booking_type' => $booking_type,
                'start_time' => $_POST['start_time'] ?? '',
                'hours' => $hours,
                'number_of_people' => $_POST['number_of_people'] ?? '',
                'meeting_location' => $_POST['meeting_location'] ?? '',
                'special_requests' => $_POST['special_requests'] ?? '',
                'total_amount' => $total_amount
            ];
            $this->loadView('tourGuides/confirmBooking', $data);
        } else {
            redirect('tourGuide/browse');
        }
    }

    /**
     * Save booking to database
     */
    public function saveBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get data from confirmation form
            $guide_id = $_POST['guide_id'] ?? '';
            $booking_date = $_POST['booking_date'] ?? '';
            $booking_type = $_POST['booking_type'] ?? '';
            $start_time = $_POST['start_time'] ?? '';
            $hours = $_POST['hours'] ?? '';
            $number_of_people = $_POST['number_of_people'] ?? '';
            $meeting_location = $_POST['meeting_location'] ?? '';
            $special_requests = $_POST['special_requests'] ?? '';
            $user_id = $_SESSION['user_id'] ?? null;

            // Calculate end_time and total_price
            if ($booking_type === 'hourly') {
                $end_time = date('H:i:s', strtotime($start_time) + ($hours * 3600));
                $total_hours = $hours;
                $guide = $this->guideModel->getGuideById($guide_id);
                $total_price = $guide ? $guide->hourly_rate * $hours : 0;
            } else {
                $start_time = '09:00:00';
                $end_time = '17:00:00';
                $total_hours = 8;
                $guide = $this->guideModel->getGuideById($guide_id);
                $total_price = $guide ? $guide->daily_rate : 0;
            }

            // Call model to save booking
            $result = $this->guideModel->createBooking([
                'guide_id' => $guide_id,
                'user_id' => $user_id,
                'booking_date' => $booking_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'total_hours' => $total_hours,
                'total_price' => $total_price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'special_requests' => $special_requests,
                'number_of_people' => $number_of_people,
                'meeting_location' => $meeting_location
            ]);

            if ($result) {
                flash('success_message', 'Your booking request has been submitted!', 'alert alert-success');
                redirect('tourGuide/profile/' . $guide_id);
            } else {
                flash('error_message', 'Failed to submit booking. Please try again.', 'alert alert-danger');
                redirect('tourGuide/profile/' . $guide_id);
            }
        } else {
            redirect('tourGuide/browse');
        }
    }

    /**
     * Search for tour guides based on query
     */
    public function search() {
        // Get the search query from GET parameter
        $query = isset($_GET['q']) ? sanitize($_GET['q']) : '';
        
        // If no query provided, redirect to browse page
        if (empty($query)) {
            redirect('tourGuide/browse');
        }
        
        // Call model to search for guides
        $guides = $this->guideModel->searchGuides($query);
        
        // If no guides found, show message
        if (empty($guides)) {
            flash('search_message', 'No guides found matching "' . htmlspecialchars($query) . '". Try a different search term.', 'alert alert-info');
        }
        
        // Data to be passed to the view
        $data = [
            'title' => 'Search Results for "' . htmlspecialchars($query) . '"',
            'query' => $query,
            'guides' => $guides,
            'result_count' => count($guides)
        ];
        
        // Load view - using browse view to display results
        $this->loadView('tourGuides/search', $data);
    }
    
    /**
     * Load a view with the header and footer
     * 
     * @param string $view The view to load
     * @param array $data The data to pass to the view
     */
    private function loadView($view, $data = []) {
        // Extract data to make it available in the view
        extract($data);
        
        // If we're loading the categories view, filter out extra categories
        if ($view === 'tourGuides/category/categories' && isset($guide_categories)) {
            $filtered_categories = [];
            foreach ($guide_categories as $category) {
                // Skip Architecture, History, Nature & Wildlife, and Off The Beaten Path categories
                if (!in_array($category->name, ['Architecture', 'History', 'Nature & Wildlife', 'Off The Beaten Path'])) {
                    $filtered_categories[] = $category;
                }
            }
            $guide_categories = $filtered_categories;
        }
        
        // Load header
        require_once VIEW_PATH . '/shares/header.php';
        
        // Load the requested view
        require_once VIEW_PATH . '/' . $view . '.php';
        
        // Load footer
        require_once VIEW_PATH . '/shares/footer.php';
    }

    // Accept booking
    public function acceptBooking($bookingId) {
        $this->guideModel->updateBookingStatus($bookingId, 'accepted');
        // Redirect or show confirmation
    }

    // Decline booking
    public function declineBooking($bookingId) {
        $this->guideModel->updateBookingStatus($bookingId, 'declined');
        // Redirect or show confirmation
    }

    // Chat view
    public function chat($bookingId) {
        $messageModel = new MessageModel();
        $messages = $messageModel->getMessages($bookingId);
        // Load chat view with $messages
    }

    // Hiển thị trang review
    public function review($guide_id) {
        // Kiểm tra đăng nhập
        if (!isLoggedIn()) {
            redirect('account/login');
        }

        // Lấy thông tin guide
        $guide = $this->guideModel->getGuideById($guide_id);
        
        if (!$guide) {
            die('Guide not found');
        }

        // Kiểm tra xem người dùng có phải là chính guide này không
        if ($_SESSION['user_id'] == $guide->user_id) {
            die('You cannot review yourself');
        }

        $data = [
            'guide' => $guide
        ];

        $this->loadView('tourGuides/Accounts/review', $data);
    }

    // Xử lý submit review
    public function submitReview() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('');
        }

        // Kiểm tra đăng nhập
        if (!isLoggedIn()) {
            redirect('account/login');
        }

        // Validate dữ liệu
        $guide_id = $_POST['guide_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $review_text = $_POST['review_text'] ?? null;

        if (!$guide_id || !$rating || !$review_text) {
            die('Missing required fields');
        }

        // Validate rating
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            die('Invalid rating');
        }

        // Lấy thông tin guide
        $guide = $this->guideModel->getGuideById($guide_id);
        if (!$guide) {
            die('Guide not found');
        }

        // Kiểm tra xem người dùng có phải là chính guide này không
        if ($_SESSION['user_id'] == $guide->user_id) {
            die('You cannot review yourself');
        }

        // Lưu review
        $review = [
            'user_id' => $_SESSION['user_id'],
            'guide_id' => $guide_id,
            'rating' => $rating,
            'review_text' => $review_text,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->reviewModel->addReview($review)) {
            // Cập nhật rating trung bình của guide
            $this->guideModel->updateAverageRating($guide_id);
            redirect('tourGuide/profile/' . $guide_id);
        } else {
            die('Something went wrong');
        }
    }

    public function deleteAccount()
    {
        if (!isLoggedIn() || $_SESSION['user_type'] !== 'guide') {
            redirect('account/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $userModel = new UserModel();
            $user = $userModel->getUserById($_SESSION['user_id']);

            // Verify password
            if (!password_verify($password, $user->password)) {
                flash('guide_message', 'Invalid password.', 'alert alert-danger');
                redirect('tourGuide/settings');
                return;
            }

            try {
                $guideModel = new GuideModel();
                $guide = $guideModel->getGuideByUserId($_SESSION['user_id']);
                
                if (!$guide) {
                    flash('guide_message', 'Guide profile not found.', 'alert alert-danger');
                    redirect('tourGuide/settings');
                    return;
                }

                // Start transaction
                $db = new Database();
                $db->beginTransaction();

                try {
                    // Delete from guide_languages
                    $db->query('DELETE FROM guide_languages WHERE guide_id = :id');
                    $db->bind(':id', $guide->id);
                    $db->execute();

                    // Delete from guide_specialties
                    $db->query('DELETE FROM guide_specialties WHERE guide_id = :id');
                    $db->bind(':id', $guide->id);
                    $db->execute();

                    // Delete from guide_reviews
                    $db->query('DELETE FROM guide_reviews WHERE guide_id = :id');
                    $db->bind(':id', $guide->id);
                    $db->execute();

                    // Delete from bookings
                    $db->query('DELETE FROM bookings WHERE guide_id = :id');
                    $db->bind(':id', $guide->id);
                    $db->execute();

                    // Delete from guide_profiles
                    $db->query('DELETE FROM guide_profiles WHERE id = :id');
                    $db->bind(':id', $guide->id);
                    $db->execute();

                    // Update user type back to regular user
                    $db->query('UPDATE users SET user_type = "user" WHERE id = :user_id');
                    $db->bind(':user_id', $_SESSION['user_id']);
                    $db->execute();

                    // Commit transaction
                    $db->commit();

                    // Update session
                    $_SESSION['user_type'] = 'user';

                    flash('user_message', 'Your guide account has been successfully deleted.', 'alert alert-success');
                    redirect('account/settings');
                } catch (Exception $e) {
                    // Rollback on error
                    $db->rollBack();
                    error_log("Error deleting guide account: " . $e->getMessage());
                    flash('guide_message', 'Error deleting account. Please try again.', 'alert alert-danger');
                    redirect('tourGuide/settings');
                }
            } catch (Exception $e) {
                error_log("Error in deleteAccount: " . $e->getMessage());
                flash('guide_message', 'Error deleting account. Please try again.', 'alert alert-danger');
                redirect('tourGuide/settings');
            }
        } else {
            redirect('tourGuide/settings');
        }
    }

    public function settings()
    {
        // Check if user is logged in and is a guide
        if (!isLoggedIn() || $_SESSION['user_type'] !== 'guide') {
            redirect('account/login');
        }

        // Get guide profile
        $guide = $this->guideModel->getGuideByUserId($_SESSION['user_id']);
        
        if (!$guide) {
            flash('guide_message', 'Guide profile not found.', 'alert alert-danger');
            redirect('account/settings');
        }

        $data = [
            'guide' => $guide
        ];

        $this->loadView('tourGuides/accoutSettings/settings', $data);
    }
} 