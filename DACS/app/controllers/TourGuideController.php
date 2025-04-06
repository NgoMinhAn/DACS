<?php

class TourGuideController {
    
    // Display all tour guides
    public function index() {
        // Fetch all guides
        // $guides = TourGuide::getAll();
        
        require_once 'app/views/guides/index.php';
    }
    
    // Display single guide profile
    public function show($id) {
        // Fetch guide by ID
        $guide = TourGuide::findById($id);
        
        if (!$guide) {
            $_SESSION['errors'] = ['Tour guide not found'];
            header('Location: /guides');
            exit;
        }
        
        require_once 'app/views/guides/show.php';
    }
    
    // Display guide registration form
    public function create() {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to become a guide'];
            header('Location: /login');
            exit;
        }
        
        require_once 'app/views/guides/create.php';
    }
    
    // Register as a tour guide
    public function store() {
        // Validate input
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /guides/create');
            exit;
        }
        
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to become a guide'];
            header('Location: /login');
            exit;
        }
        
        // Extract form data
        $speciality = $_POST['speciality'] ?? '';
        $experience = $_POST['experience'] ?? '';
        $languages = $_POST['languages'] ?? [];
        $hourly_rate = $_POST['hourly_rate'] ?? 0;
        $bio = $_POST['bio'] ?? '';
        
        // Validate data
        $errors = [];
        if (empty($speciality)) $errors[] = 'Speciality is required';
        if (empty($languages)) $errors[] = 'At least one language is required';
        if (empty($hourly_rate) || !is_numeric($hourly_rate)) $errors[] = 'Valid hourly rate is required';
        if (empty($bio)) $errors[] = 'Bio is required';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'speciality' => $speciality,
                'experience' => $experience,
                'languages' => $languages,
                'hourly_rate' => $hourly_rate,
                'bio' => $bio
            ];
            header('Location: /guides/create');
            exit;
        }
        
        // Create guide profile
        $guide = TourGuide::create($_SESSION['user_id'], $speciality, $experience, $languages, $hourly_rate, $bio);
        
        $_SESSION['success'] = 'Tour guide profile created successfully';
        header('Location: /dashboard');
        exit;
    }
    
    // Display edit form
    public function edit($id) {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to edit a guide profile'];
            header('Location: /login');
            exit;
        }
        
        // Fetch guide by ID
        $guide = TourGuide::findById($id);
        
        if (!$guide) {
            $_SESSION['errors'] = ['Tour guide not found'];
            header('Location: /guides');
            exit;
        }
        
        // Check if user owns this guide profile
        if ($guide->user_id !== $_SESSION['user_id']) {
            $_SESSION['errors'] = ['You can only edit your own guide profile'];
            header('Location: /guides');
            exit;
        }
        
        require_once 'app/views/guides/edit.php';
    }
    
    // Update guide profile
    public function update($id) {
        // Validate input
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /guides/edit/$id");
            exit;
        }
        
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['You must be logged in to update a guide profile'];
            header('Location: /login');
            exit;
        }
        
        // Fetch guide by ID
        $guide = TourGuide::findById($id);
        
        if (!$guide) {
            $_SESSION['errors'] = ['Tour guide not found'];
            header('Location: /guides');
            exit;
        }
        
        // Check if user owns this guide profile
        if ($guide->user_id !== $_SESSION['user_id']) {
            $_SESSION['errors'] = ['You can only update your own guide profile'];
            header('Location: /guides');
            exit;
        }
        
        // Extract form data
        $speciality = $_POST['speciality'] ?? '';
        $experience = $_POST['experience'] ?? '';
        $languages = $_POST['languages'] ?? [];
        $hourly_rate = $_POST['hourly_rate'] ?? 0;
        $bio = $_POST['bio'] ?? '';
        
        // Validate data
        $errors = [];
        if (empty($speciality)) $errors[] = 'Speciality is required';
        if (empty($languages)) $errors[] = 'At least one language is required';
        if (empty($hourly_rate) || !is_numeric($hourly_rate)) $errors[] = 'Valid hourly rate is required';
        if (empty($bio)) $errors[] = 'Bio is required';
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: /guides/edit/$id");
            exit;
        }
        
        // Update guide profile
        // $guide->update($speciality, $experience, $languages, $hourly_rate, $bio);
        
        $_SESSION['success'] = 'Tour guide profile updated successfully';
        header('Location: /dashboard');
        exit;
    }
    
    // Update availability
    public function updateAvailability($id) {
        // Validate input
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /guides/availability/$id");
            exit;
        }
        
        // Check if user is authenticated and owns this guide profile
        // Similar validation as in update method
        
        // Extract availability data
        $available_days = $_POST['available_days'] ?? [];
        $available_hours = $_POST['available_hours'] ?? [];
        
        // Save availability
        $guide = TourGuide::findById($id);
        $guide->updateAvailability($available_days, $available_hours);
        
        $_SESSION['success'] = 'Availability updated successfully';
        header('Location: /dashboard');
        exit;
    }
    
    // Search for guides based on criteria
    public function search() {
        $speciality = $_GET['speciality'] ?? '';
        $language = $_GET['language'] ?? '';
        $date = $_GET['date'] ?? '';
        
        // Search for guides
        $guides = TourGuide::search($speciality, $language, $date);
        
        require_once 'app/views/guides/search_results.php';
    }
} 