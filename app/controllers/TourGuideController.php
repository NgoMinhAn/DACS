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
            
            if (isset($_GET['rating']) && !empty($_GET['rating'])) {
                $filters['rating'] = sanitize($_GET['rating']);
            }
        }
        
        // Get all languages and specialties for filter options and to normalize incoming filter values
        $languages = $this->guideModel->getAllLanguages();
        $specialties = $this->guideModel->getAllSpecialties();

        // Normalize filters: the view sends language codes and specialty IDs, but guide_listings stores names
        $normalizedFilters = $filters; // copy original for sending back to view

        // If a language code was provided, map it to the language name used in guide_listings
        if (!empty($filters['language'])) {
            $langName = null;
            foreach ($languages as $lang) {
                if (isset($lang->code) && $lang->code === $filters['language']) {
                    $langName = $lang->name;
                    break;
                }
            }
            // If we found a name, use it for SQL LIKE matching; otherwise keep original value (safe fallback)
            if ($langName) $normalizedFilters['language'] = $langName;
        }

        // If a specialty id was provided, map it to the specialty name used in guide_listings
        if (!empty($filters['specialty'])) {
            $specName = null;
            // filters['specialty'] may be an id or a name; if numeric, try to map
            if (is_numeric($filters['specialty'])) {
                foreach ($specialties as $s) {
                    if (isset($s->id) && (string)$s->id === (string)$filters['specialty']) {
                        $specName = $s->name;
                        break;
                    }
                }
            } else {
                // non-numeric, perhaps the UI already sent a name
                $specName = $filters['specialty'];
            }
            if ($specName) $normalizedFilters['specialty'] = $specName;
        }

        // Get guides from database with normalized filters
        $guides = $this->guideModel->getAllGuides($normalizedFilters);
        
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
        
        // Recommendation logic: prefer local booking/search history based recommendations
        $recommended_guides = [];
        $ai_debug = '';
        try {
            // If the user is logged in, try to build personalized recommendations
            if (isset($_SESSION['user_id'])) {
                // Load booking model to examine past bookings
                require_once MODEL_PATH . '/BookingModel.php';
                $bookingModel = new BookingModel();

                // Get user's past bookings and use GuideModel to recommend
                $userBookings = $bookingModel->getUserBookings($_SESSION['user_id']);

                // Use GuideModel helper (new) to fetch recommendations
                $recommended_guides = $this->guideModel->getRecommendedGuidesForUser($_SESSION['user_id'], 6);

                // Normalize and update each recommended guide with accurate review counts and default ratings
                if (!empty($recommended_guides)) {
                    foreach ($recommended_guides as $rg) {
                        // Ensure avg_rating field exists
                        if (!isset($rg->avg_rating)) $rg->avg_rating = 0;

                        // Try to resolve guide_profiles.id reliably
                        $gid = null;
                        if (!empty($rg->guide_id)) {
                            $gid = $rg->guide_id;
                        } elseif (!empty($rg->id)) {
                            $gid = $rg->id;
                        } elseif (!empty($rg->user_id)) {
                            // Map from users.id to guide_profiles.id if necessary
                            $guideRow = $this->guideModel->getGuideByUserId($rg->user_id);
                            if ($guideRow && !empty($guideRow->id)) {
                                $gid = $guideRow->id;
                            }
                        }

                        if ($gid) {
                            // Fetch accurate approved review count
                            $rg->total_reviews = $this->guideModel->getApprovedReviewCount($gid);
                        } else {
                            // Fall back to any total_reviews value present or zero
                            $rg->total_reviews = $rg->total_reviews ?? 0;
                        }
                    }
                }

                // If no personalized results, fall back to top rated
                if (empty($recommended_guides)) {
                    $recommended_guides = $this->guideModel->getTopRatedGuides(6);
                    $ai_debug = 'No personalized data found; showing top-rated guides.';
                }
            } else {
                // Not logged in: show top-rated guides
                $recommended_guides = $this->guideModel->getTopRatedGuides(6);
                $ai_debug = 'You are not logged in; showing top-rated guides.';
            }
        } catch (Exception $e) {
            // On any error, fall back to top-rated guides
            $recommended_guides = $this->guideModel->getTopRatedGuides(6);
            $ai_debug = 'Recommendation error: ' . $e->getMessage();
        }

        // Ensure recommended guides always show accurate review counts and ratings
        if (!empty($recommended_guides)) {
            // Resolve guide profile ids for all recommended items
            $resolved = [];
            foreach ($recommended_guides as $idx => $rg) {
                if (!isset($rg->avg_rating)) $rg->avg_rating = 0;

                $gid = null;
                if (!empty($rg->guide_id)) {
                    $gid = $rg->guide_id;
                } elseif (!empty($rg->id)) {
                    $gid = $rg->id;
                } elseif (!empty($rg->user_id)) {
                    $guideRow = $this->guideModel->getGuideByUserId($rg->user_id);
                    if ($guideRow && !empty($guideRow->id)) {
                        $gid = $guideRow->id;
                    }
                }

                $resolved[$idx] = $gid;
            }

            // Bulk fetch counts for resolved gids
            $gids = array_filter(array_values($resolved));
            if (!empty($gids)) {
                // Build placeholder list
                $placeholders = implode(',', array_map(fn($i) => ':g' . $i, array_keys($gids)));
                $db = new Database();
                $sql = 'SELECT guide_id, COUNT(*) AS cnt FROM guide_reviews WHERE status = "approved" AND guide_id IN (' . $placeholders . ') GROUP BY guide_id';
                $db->query($sql);
                // Bind gids to placeholders
                $i = 0;
                foreach ($gids as $g) {
                    $db->bind(':g' . $i, $g);
                    $i++;
                }

                // Debug: log resolved gids
                error_log("[DEBUG][Recommendations] Resolved gids for recommended items: " . implode(',', $gids));

                $rows = [];
                try {
                    $rows = $db->resultSet();
                } catch (Exception $e) {
                    $rows = [];
                }

                // Debug: log raw grouped rows returned from guide_reviews
                $debugRows = [];
                foreach ($rows as $r) {
                    $debugRows[] = json_encode(['guide_id' => $r->guide_id, 'cnt' => $r->cnt]);
                }
                error_log("[DEBUG][Recommendations] guide_reviews grouped rows: " . implode(';', $debugRows));

                $counts = [];
                foreach ($rows as $r) {
                    $counts[$r->guide_id] = $r->cnt;
                }

                // Assign counts back to recommended_guides and log mapping
                foreach ($recommended_guides as $idx => $rg) {
                    $gid = $resolved[$idx] ?? null;
                    if ($gid && isset($counts[$gid])) {
                        $rg->total_reviews = $counts[$gid];
                    } else {
                        $rg->total_reviews = $rg->total_reviews ?? 0;
                    }
                    error_log("[DEBUG][Recommendations] recommended_idx={$idx} guide_profile_id=" . ($gid ?? 'NULL') . " mapped_count=" . ($rg->total_reviews ?? '0') . " title=" . ($rg->name ?? ($rg->title ?? 'unknown')));
                }
            } else {
                // No gids resolved - leave defaults
                foreach ($recommended_guides as $rg) {
                    $rg->total_reviews = $rg->total_reviews ?? 0;
                }
                error_log("[DEBUG][Recommendations] No gids resolved for recommended items.");
            }
        }

        // Data to be passed to the view
        $data = [
            'title' => 'Browse All Guides',
            'guides' => $guides,
            'languages' => $languages,
            'specialties' => $specialties,
            'current_filters' => $filters,
            'recommended_guides' => $recommended_guides,
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
        
        // Get category info from database
        $categoryModel = new CategoryModel();
        $categoryInfo = null;
        
        // Handle different category types
        switch ($type) {
            case 'city':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('City');
                $title = 'City Tour Guides';
                $categoryInfo = $categoryModel->getCategoryByName('City');
                if (!$categoryInfo) {
                    $categoryInfo = $categoryModel->getCategoryByName('City Tours');
                }
                break;
            case 'adventure':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('Adventure');
                $title = 'Adventure Tour Guides';
                $categoryInfo = $categoryModel->getCategoryByName('Adventure');
                break;
            case 'cultural':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('Cultural');
                $title = 'Cultural Tour Guides';
                $categoryInfo = $categoryModel->getCategoryByName('Cultural');
                if (!$categoryInfo) {
                    $categoryInfo = $categoryModel->getCategoryByName('Cultural Immersion');
                }
                break;
            case 'food':
                $categoryGuides = $this->guideModel->getGuidesBySpecialty('Food');
                $title = 'Food & Cuisine Guides';
                $categoryInfo = $categoryModel->getCategoryByName('Food');
                if (!$categoryInfo) {
                    $categoryInfo = $categoryModel->getCategoryByName('Food & Cuisine');
                }
                break;
            case 'historical':
            case 'history':
                // Redirect to browse instead of showing historical tours
                redirect('tourGuide/browse');
                break;
            case 'categories':
                // Get all categories using CategoryModel to get all without filters
                $categoryModel = new CategoryModel();
                $guideCategories = $categoryModel->getAllCategories();
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
                
                // Convert slug to potential category names to search in database
                // URL format from categories page: strtolower(str_replace(' & ', '-', str_replace(' ', '-', $category->name)))
                // So we need to reverse this: "food-cuisine" -> "Food & Cuisine" or "Food Cuisine"
                $potentialNames = [];
                
                // First, try the specialty map
                if (isset($specialtyMap[$type])) {
                    $potentialNames[] = $specialtyMap[$type];
                }
                
                // Convert slug to readable format: "food-cuisine" -> "Food Cuisine"
                $slugToName = ucwords(str_replace('-', ' ', $type));
                $potentialNames[] = $slugToName;
                
                // Try with ampersand: "food-cuisine" -> "Food & Cuisine"
                // Common patterns: "food-cuisine" should try "Food & Cuisine"
                // Check if the slug might represent something with "&"
                $slugWithAmpersand = str_replace('-and-', ' & ', $type);
                $slugWithAmpersand = str_replace('-', ' ', $slugWithAmpersand);
                $slugWithAmpersand = ucwords($slugWithAmpersand);
                if ($slugWithAmpersand !== $slugToName) {
                    $potentialNames[] = $slugWithAmpersand;
                }
                
                // Also try replacing common word boundaries with " & "
                // For example: "food-cuisine" -> "Food & Cuisine"
                // This is a heuristic: if slug has 2+ words, try adding " & " between common pairs
                $words = explode('-', $type);
                if (count($words) >= 2) {
                    // Try "FirstWord & SecondWord Rest"
                    $ampersandName = ucwords($words[0]) . ' & ' . ucwords(implode(' ', array_slice($words, 1)));
                    $potentialNames[] = $ampersandName;
                }
                
                // Try direct match with slug (original and capitalized)
                $potentialNames[] = $type;
                $potentialNames[] = ucfirst($type);
                
                // Remove duplicates
                $potentialNames = array_unique($potentialNames);
                
                // Try to find category in database first
                $categoryInfo = null;
                $foundCategoryName = null;
                
                foreach ($potentialNames as $name) {
                    $categoryInfo = $categoryModel->getCategoryByName($name);
                    if ($categoryInfo) {
                        $foundCategoryName = $name;
                        error_log("Found category in database: " . $name);
                        break;
                    }
                }
                
                // If category found in database, use its name to search for guides
                if ($categoryInfo) {
                    // Category exists in database, search for guides (even if empty)
                    $categoryGuides = $this->guideModel->getGuidesBySpecialty($foundCategoryName);
                    // Ensure it's an array
                    if (!is_array($categoryGuides)) {
                        $categoryGuides = [];
                    }
                    $title = $categoryInfo->name . ' Tour Guides';
                    error_log("Category found in DB: " . $foundCategoryName . ", guides count: " . count($categoryGuides));
                } else {
                    // Category not in database, try to find guides by specialty name
                    error_log("Category not found in database, trying specialty mapping");
                
                // Check if we have a direct mapping for this slug
                if (isset($specialtyMap[$type])) {
                    $exactSpecialty = $specialtyMap[$type];
                    error_log("Found exact specialty mapping: $type -> $exactSpecialty");
                    $categoryGuides = $this->guideModel->getGuidesBySpecialty($exactSpecialty);
                        $displayTitle = $exactSpecialty;
                } else {
                    // Try different capitalizations and formats
                    $categoryGuides = [];
                    
                    // Try direct match first
                    $categoryGuides = $this->guideModel->getGuidesBySpecialty($type);
                        $displayTitle = $type;
                    
                    // If no results, try with first letter capitalized
                    if (empty($categoryGuides)) {
                        $specialty = ucfirst($type);
                        $categoryGuides = $this->guideModel->getGuidesBySpecialty($specialty);
                            $displayTitle = $specialty;
                        error_log("Trying with ucfirst: " . $specialty);
                    }
                    
                    // If still no results, try replacing dashes with spaces
                    if (empty($categoryGuides)) {
                        $specialty = str_replace('-', ' ', $type);
                        $specialty = ucwords($specialty); // Capitalize first letter of each word
                        $categoryGuides = $this->guideModel->getGuidesBySpecialty($specialty);
                            $displayTitle = $specialty;
                        error_log("Trying with dashes replaced by spaces: " . $specialty);
                    }
                    
                    // If still no results, try replacing dashes with ampersand
                    if (empty($categoryGuides)) {
                        $specialty = str_replace('-and-', ' & ', $type);
                        $specialty = str_replace('-', ' ', $specialty);
                        $specialty = ucwords($specialty);
                        $categoryGuides = $this->guideModel->getGuidesBySpecialty($specialty);
                            $displayTitle = $specialty;
                        error_log("Trying with ampersand: " . $specialty);
                    }
                }
                
                    // If no guides found and category not in database, redirect
                if (empty($categoryGuides)) {
                    flash('error_message', 'No guides found for this specialty.', 'alert alert-info');
                        error_log("No guides found for any variation of: " . $type . " and category not in database");
                    redirect('tourGuide/browse');
                }
                
                $title = $displayTitle . ' Tour Guides';
                    
                    // Try to get category info from database using the found name
                    $categoryInfo = $categoryModel->getCategoryByName($displayTitle);
                    if (!$categoryInfo && isset($specialtyMap[$type])) {
                        $categoryInfo = $categoryModel->getCategoryByName($specialtyMap[$type]);
                    }
                }
                
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
        
        // Ensure categoryGuides is always an array
        if (!is_array($categoryGuides)) {
            $categoryGuides = [];
        }
        
        // Data to be passed to the view
        $data = [
            'title' => $title,
            'category_guides' => $categoryGuides,
            'category_info' => $categoryInfo
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
    /**
     * Get display name for location code
     */
    private function getLocationDisplayName($locationCode)
    {
        $locationNames = [
            'hanoi' => 'Hà Nội',
            'hochiminh' => 'Hồ Chí Minh',
            'danang' => 'Đà Nẵng',
            'hue' => 'Huế',
            'halong' => 'Hạ Long',
            'sapa' => 'Sapa',
            'nhatrang' => 'Nha Trang',
            'dalat' => 'Đà Lạt'
        ];
        
        return isset($locationNames[$locationCode]) ? $locationNames[$locationCode] : $locationCode;
    }
    
    public function search() {
        // Get the search query and location from GET parameters
        $query = isset($_GET['q']) ? trim(sanitize($_GET['q'])) : '';
        $location = isset($_GET['location']) ? trim(sanitize($_GET['location'])) : '';
        
        // If no query and no location provided, redirect to browse page
        if (empty($query) && empty($location)) {
            redirect('tourGuide/browse');
        }
        
        // Call model to search for guides
        $guides = $this->guideModel->searchGuides($query, $location);
        
        // Update guides with accurate review counts
        if (!empty($guides)) {
            foreach ($guides as $guide) {
                $guideId = $guide->guide_id ?? $guide->id ?? 0;
                if ($guideId > 0) {
                    $approvedReviewCount = $this->guideModel->getApprovedReviewCount($guideId);
                    $guide->total_reviews = $approvedReviewCount;
                }
            }
        }
        
        // Build search message with proper location display name
        $searchMessage = '';
        if (!empty($query)) {
            $searchMessage = htmlspecialchars($query);
        }
        if (!empty($location)) {
            $locationDisplay = $this->getLocationDisplayName($location);
            $searchMessage .= ($searchMessage ? ' tại ' : '') . $locationDisplay;
        }
        
        // If no guides found, show message
        if (empty($guides)) {
            $message = !empty($searchMessage) 
                ? 'Không tìm thấy hướng dẫn viên nào phù hợp với "' . $searchMessage . '". Vui lòng thử từ khóa khác.' 
                : 'Không tìm thấy hướng dẫn viên nào. Vui lòng thử lại.';
            flash('search_message', $message, 'alert alert-info');
        }
        
        // Data to be passed to the view
        $data = [
            'title' => 'Kết quả tìm kiếm' . (!empty($searchMessage) ? ' cho "' . $searchMessage . '"' : ''),
            'query' => $query,
            'location' => $location,
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