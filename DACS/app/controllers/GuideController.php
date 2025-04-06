<?php
/**
 * Guide Controller
 * Handles guide-related functionality
 */
class GuideController {
    /**
     * Redirect to specified path
     */
    private function redirect($path) {
        header("Location: $path");
        exit;
    }
    
    /**
     * Display list of guides with search/filter options
     */
    public function index() {
        $guideModel = new Guide();
        
        // Get filter parameters
        $specialty = $_GET['specialty'] ?? null;
        $language = $_GET['language'] ?? null;
        $location = $_GET['location'] ?? null;
        $date = $_GET['date'] ?? null;
        $page = $_GET['page'] ?? 1;
        
        // Get guides with filters
        $guides = $guideModel->getAll([
            'specialty' => $specialty,
            'language' => $language,
            'location' => $location,
            'date' => $date
        ], $page, 6);
        
        // Get total count for pagination
        $totalGuides = $guideModel->count([
            'specialty' => $specialty,
            'language' => $language,
            'location' => $location,
            'date' => $date
        ]);
        
        $totalPages = ceil($totalGuides / 6);
        
        // Get all specialties and languages for filter dropdowns
        $specialties = $guideModel->getAllSpecialties();
        $languages = $guideModel->getAllLanguages();
        
        // Load view
        include VIEWS_PATH . '/guides/index.php';
    }
    
    /**
     * Display single guide profile
     */
    public function show($id) {
        $guideModel = new Guide();
        $guide = $guideModel->findById($id);
        
        if (!$guide) {
            $_SESSION['error'] = 'Guide not found';
            $this->redirect('/guides');
            return;
        }
        
        // Get guide's reviews
        $reviewModel = new Review();
        $reviews = $reviewModel->getByGuideId($id, 1, 5);
        $totalReviews = $reviewModel->countByGuideId($id);
        
        // Get guide's availability
        $availability = $guideModel->getAvailability($id);
        
        // Load view
        include VIEWS_PATH . '/guides/show.php';
    }
    
    /**
     * Show form to create a guide profile
     */
    public function create() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to become a guide';
            $this->redirect('/login');
            return;
        }
        
        // Check if user is already a guide
        $guideModel = new Guide();
        $userId = $_SESSION['user_id'];
        $existingGuide = $guideModel->findByUserId($userId);
        
        if ($existingGuide) {
            $this->redirect('/guides/profile');
            return;
        }
        
        // Load create view
        include VIEWS_PATH . '/guides/create.php';
    }
    
    /**
     * Process guide profile creation
     */
    public function store() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to become a guide';
            $this->redirect('/login');
            return;
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/guides/create');
            return;
        }
        
        // Validate form data
        $userId = $_SESSION['user_id'];
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $hourly_rate = $_POST['hourly_rate'] ?? 0;
        $languages = $_POST['languages'] ?? [];
        $specialties = $_POST['specialties'] ?? [];
        
        // Validate inputs
        $errors = [];
        if (empty($title)) $errors[] = 'Title is required';
        if (empty($description)) $errors[] = 'Description is required';
        if (!is_numeric($hourly_rate) || $hourly_rate <= 0) $errors[] = 'Hourly rate must be a positive number';
        if (empty($languages)) $errors[] = 'Select at least one language';
        if (empty($specialties)) $errors[] = 'Select at least one specialty';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'title' => $title,
                'description' => $description,
                'hourly_rate' => $hourly_rate,
                'languages' => $languages,
                'specialties' => $specialties
            ];
            $this->redirect('/guides/create');
            return;
        }
        
        // Handle profile image upload
        $profileImage = 'default-guide.jpg'; // Default image
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/guides/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = uniqid('guide_') . '_' . basename($_FILES['profile_image']['name']);
            $uploadFile = $uploadDir . $fileName;
            
            // Check if image file is an actual image
            $check = getimagesize($_FILES['profile_image']['tmp_name']);
            if ($check !== false) {
                // Upload file
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                    $profileImage = $fileName;
                }
            }
        }
        
        // Create guide profile
        $guideModel = new Guide();
        $guideId = $guideModel->create([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'hourly_rate' => $hourly_rate,
            'profile_image' => $profileImage,
            'languages' => $languages,
            'specialties' => $specialties
        ]);
        
        if (!$guideId) {
            $_SESSION['error'] = 'An error occurred while creating your guide profile';
            $this->redirect('/guides/create');
            return;
        }
        
        // Update user's is_guide status if needed
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        if (!$user['is_guide']) {
            $userModel->update($userId, ['is_guide' => 1]);
            $_SESSION['is_guide'] = 1;
        }
        
        $_SESSION['success'] = 'Guide profile created successfully!';
        $this->redirect('/guides/profile');
    }
    
    /**
     * Show guide's own profile dashboard
     */
    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your guide profile';
            $this->redirect('/login');
            return;
        }
        
        // Check if user is a guide
        if (!isset($_SESSION['is_guide']) || !$_SESSION['is_guide']) {
            $this->redirect('/guides/create');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $guideModel = new Guide();
        $guide = $guideModel->findByUserId($userId);
        
        if (!$guide) {
            $_SESSION['error'] = 'Guide profile not found';
            $this->redirect('/');
            return;
        }
        
        // Get bookings, reviews, etc.
        $bookingModel = new Booking();
        $page = $_GET['page'] ?? 1;
        $bookings = $bookingModel->getByGuideId($guide['id'], $page, 5);
        $totalBookings = $bookingModel->countByGuideId($guide['id']);
        $totalPages = ceil($totalBookings / 5);
        
        // Get upcoming bookings
        $upcomingBookings = $bookingModel->getUpcomingByGuideId($guide['id'], 5);
        
        // Load view
        include VIEWS_PATH . '/guides/profile.php';
    }
    
    /**
     * Show form to edit guide profile
     */
    public function edit() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to edit your guide profile';
            $this->redirect('/login');
            return;
        }
        
        // Check if user is a guide
        if (!isset($_SESSION['is_guide']) || !$_SESSION['is_guide']) {
            $this->redirect('/guides/create');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $guideModel = new Guide();
        $guide = $guideModel->findByUserId($userId);
        
        if (!$guide) {
            $_SESSION['error'] = 'Guide profile not found';
            $this->redirect('/');
            return;
        }
        
        // Get languages and specialties
        $guideLanguages = $guideModel->getGuideLanguages($guide['id']);
        $guideSpecialties = $guideModel->getGuideSpecialties($guide['id']);
        
        // Get all available languages and specialties for dropdowns
        $allLanguages = $guideModel->getAllLanguages();
        $allSpecialties = $guideModel->getAllSpecialties();
        
        // Load view
        include VIEWS_PATH . '/guides/edit.php';
    }
    
    /**
     * Update guide profile
     */
    public function update() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to update your guide profile';
            $this->redirect('/login');
            return;
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/guides/edit');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $guideModel = new Guide();
        $guide = $guideModel->findByUserId($userId);
        
        if (!$guide) {
            $_SESSION['error'] = 'Guide profile not found';
            $this->redirect('/');
            return;
        }
        
        // Validate form data
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $hourly_rate = $_POST['hourly_rate'] ?? 0;
        $languages = $_POST['languages'] ?? [];
        $specialties = $_POST['specialties'] ?? [];
        
        // Validate inputs
        $errors = [];
        if (empty($title)) $errors[] = 'Title is required';
        if (empty($description)) $errors[] = 'Description is required';
        if (!is_numeric($hourly_rate) || $hourly_rate <= 0) $errors[] = 'Hourly rate must be a positive number';
        if (empty($languages)) $errors[] = 'Select at least one language';
        if (empty($specialties)) $errors[] = 'Select at least one specialty';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/guides/edit');
            return;
        }
        
        // Handle profile image upload
        $profileImage = $guide['profile_image']; // Keep existing image by default
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/guides/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = uniqid('guide_') . '_' . basename($_FILES['profile_image']['name']);
            $uploadFile = $uploadDir . $fileName;
            
            // Check if image file is an actual image
            $check = getimagesize($_FILES['profile_image']['tmp_name']);
            if ($check !== false) {
                // Upload file
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                    $profileImage = $fileName;
                }
            }
        }
        
        // Update guide profile
        $success = $guideModel->update($guide['id'], [
            'title' => $title,
            'description' => $description,
            'hourly_rate' => $hourly_rate,
            'profile_image' => $profileImage,
            'languages' => $languages,
            'specialties' => $specialties
        ]);
        
        if (!$success) {
            $_SESSION['error'] = 'An error occurred while updating your guide profile';
            $this->redirect('/guides/edit');
            return;
        }
        
        $_SESSION['success'] = 'Guide profile updated successfully!';
        $this->redirect('/guides/profile');
    }
} 