<?php

class UserController {
    
    // Display user registration form
    public function register() {
        require_once 'app/views/users/register.php';
    }
    
    /**
     * Process registration form
     */
    public function registerPost() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }
        
        // Validate form data
        $name = $_POST['name'] ?? '';
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $location = $_POST['location'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $is_guide = isset($_POST['is_guide']) ? 1 : 0;
        
        // Validate inputs
        $errors = [];
        if (empty($name)) $errors[] = 'Name is required';
        if (!$email) $errors[] = 'Valid email is required';
        if (empty($password)) $errors[] = 'Password is required';
        if ($password != $password_confirm) $errors[] = 'Passwords do not match';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
        
        // Check if email already exists
        $userModel = new User();
        if ($userModel->emailExists($email)) {
            $errors[] = 'Email is already registered';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'name' => $name,
                'email' => $email,
                'location' => $location,
                'phone' => $phone,
                'is_guide' => $is_guide
            ];
            $this->redirect('/register');
            return;
        }
        
        // Create new user
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'location' => $location,
            'phone' => $phone,
            'is_guide' => $is_guide
        ]);
        
        if (!$userId) {
            $_SESSION['errors'] = ['An error occurred during registration. Please try again.'];
            $this->redirect('/register');
            return;
        }
        
        // If registering as a guide, create a guide profile
        if ($is_guide) {
            $this->redirect('/guides/create');
            return;
        }
        
        // Set session variables for automatic login
        $_SESSION['user_id'] = $userId;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['is_guide'] = $is_guide;
        $_SESSION['is_admin'] = 0;
        
        $_SESSION['success'] = 'Registration successful! Welcome to LocalGuides.';
        $this->redirect('/');
    }
    
    // Display login form
    public function login() {
        require_once 'app/views/users/login.php';
    }
    
    /**
     * Process login form
     */
    public function loginPost() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }
        
        // Validate form data
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (!$email || empty($password)) {
            $_SESSION['errors'] = ['Please enter a valid email and password.'];
            $this->redirect('/login');
            return;
        }
        
        // Authenticate user
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);
        
        if (!$user) {
            $_SESSION['errors'] = ['Invalid email or password.'];
            $this->redirect('/login');
            return;
        }
        
        // Set session variables
        $_SESSION['user_id'] = $user->id;
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;
        $_SESSION['is_guide'] = $user->is_guide;
        $_SESSION['is_admin'] = $user->is_admin;
        
        // Set remember me cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/', '', false, true);
            
            // Update user's remember token in database
            $userModel->update($user->id, ['remember_token' => $token]);
        }
        
        $_SESSION['success'] = 'You have been successfully logged in.';
        
        // Redirect to appropriate page
        $this->redirect('/');
    }
    
    // Logout user
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
    
    /**
     * Display user profile
     */
    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your profile';
            $this->redirect('/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            $this->redirect('/');
            return;
        }
        
        // Get user's bookings
        $bookingModel = new Booking();
        $page = $_GET['page'] ?? 1;
        $bookings = $bookingModel->getByUserId($userId, $page, 5);
        $totalBookings = $bookingModel->countByUserId($userId);
        $totalPages = ceil($totalBookings / 5);
        
        // If user is a guide, get their guide profile
        $guideData = null;
        if ($user['is_guide']) {
            $guideModel = new Guide();
            $guideData = $guideModel->findByUserId($userId);
        }
        
        // Load view with data
        include VIEWS_PATH . '/users/profile.php';
    }
    
    /**
     * Show form to edit profile
     */
    public function editProfile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to edit your profile';
            $this->redirect('/login');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            $this->redirect('/');
            return;
        }
        
        // Load view with data
        include VIEWS_PATH . '/users/edit_profile.php';
    }
    
    /**
     * Update user profile
     */
    public function updateProfile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to update your profile';
            $this->redirect('/login');
            return;
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile/edit');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        
        // Validate form data
        $name = $_POST['name'] ?? '';
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $location = $_POST['location'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        // Validate inputs
        $errors = [];
        if (empty($name)) $errors[] = 'Name is required';
        if (!$email) $errors[] = 'Valid email is required';
        
        // Check if email exists and belongs to another user
        if ($userModel->emailExistsExcept($email, $userId)) {
            $errors[] = 'Email is already registered to another account';
        }
        
        // If changing password, validate
        if (!empty($newPassword)) {
            // Verify current password
            if (empty($currentPassword) || !$userModel->verifyPassword($userId, $currentPassword)) {
                $errors[] = 'Current password is incorrect';
            }
            
            if (strlen($newPassword) < 6) $errors[] = 'New password must be at least 6 characters';
            if ($newPassword != $passwordConfirm) $errors[] = 'New passwords do not match';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/profile/edit');
            return;
        }
        
        // Prepare data for update
        $data = [
            'name' => $name,
            'email' => $email,
            'location' => $location,
            'phone' => $phone,
            'bio' => $bio
        ];
        
        // Add new password if provided
        if (!empty($newPassword)) {
            $data['password'] = $newPassword;
        }
        
        // Update user
        $success = $userModel->update($userId, $data);
        
        if (!$success) {
            $_SESSION['error'] = 'An error occurred while updating your profile';
            $this->redirect('/profile/edit');
            return;
        }
        
        // Update session variables
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        
        $_SESSION['success'] = 'Profile updated successfully';
        $this->redirect('/profile');
    }
} 