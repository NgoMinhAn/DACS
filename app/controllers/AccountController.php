<?php
/**
 * Account Controller
 * Handles user authentication and account management
 */
class AccountController {
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Load the user model (will create later)
        $this->userModel = new UserModel();
    }
    
    /**
     * Index method - redirect to login
     */
    public function index() {
        redirect('account/login');
    }
    
    /**
     * Login method
     */
    public function login() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('');
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'title' => 'Log In',
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'remember_me' => isset($_POST['remember_me']) ? 1 : 0,
                'errors' => []
            ];
            
            // Validate email
            if (empty($data['email'])) {
                $data['errors']['email'] = 'Please enter your email address';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['errors']['email'] = 'Please enter a valid email address';
            }
            
            // Validate password
            if (empty($data['password'])) {
                $data['errors']['password'] = 'Please enter your password';
            }
            
            // If validation passes, attempt login
            if (empty($data['errors'])) {
                // Check if user exists and password is correct
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    // Create session
                    $this->createUserSession($loggedInUser, $data['remember_me']);
                    
                    // Redirect based on user type
                    switch ($loggedInUser->user_type) {
                        case 'admin':
                            redirect('admin/dashboard');
                            break;
                        case 'guide':
                            redirect('guide/dashboard');
                            break;
                        default:
                            redirect('');
                            break;
                    }
                } else {
                    // Login failed
                    $data['errors']['email'] = 'Invalid email or password';
                    
                    // Load view with errors
                    $this->loadView('tourGuides/Accounts/login', $data);
                }
            } else {
                // Load view with errors
                $this->loadView('tourGuides/Accounts/login', $data);
            }
        } else {
            // Display login form
            $data = [
                'title' => 'Log In',
                'email' => '',
                'password' => '',
                'remember_me' => 0,
                'errors' => []
            ];
            
            $this->loadView('tourGuides/Accounts/login', $data);
        }
    }
    
    /**
     * Register as regular user
     */
    public function register() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('');
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process registration form
            // Will implement later
            redirect('account/login');
        } else {
            // Display registration form
            $data = [
                'title' => 'Register Account',
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'errors' => []
            ];
            
            $this->loadView('tourGuides/Accounts/register', $data);
        }
    }
    
    /**
     * Register as tour guide
     */
    public function registerGuide() {
        // Redirect to guide registration form
        redirect('account/register/guide');
    }
    
    /**
     * Guide registration
     * 
     * @param string $step Optional step parameter
     */
    public function register_guide($step = 'basic') {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('');
        }
        
        // Multi-step guide registration
        switch ($step) {
            case 'basic':
                // Basic information step
                $data = [
                    'title' => 'Become a Tour Guide - Basic Information',
                    'step' => 'basic',
                    'errors' => []
                ];
                
                $this->loadView('tourGuides/Accounts/register-guide', $data);
                break;
            
            case 'profile':
                // Profile information step
                $data = [
                    'title' => 'Become a Tour Guide - Profile Details',
                    'step' => 'profile',
                    'errors' => []
                ];
                
                $this->loadView('tourGuides/Accounts/register-guide', $data);
                break;
                
            case 'complete':
                // Final step
                $data = [
                    'title' => 'Become a Tour Guide - Complete Registration',
                    'step' => 'complete',
                    'errors' => []
                ];
                
                $this->loadView('tourGuides/Accounts/register-guide', $data);
                break;
                
            default:
                redirect('account/register/guide');
                break;
        }
    }
    
    /**
     * Logout method
     */
    public function logout() {
        // Unset session variables
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_type']);
        
        // Destroy session
        session_destroy();
        
        // Redirect to login page
        redirect('account/login');
    }
    
    /**
     * Forgot password method
     */
    public function forgotPassword() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('');
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process forgot password form
            // Will implement later
            
            // Show success message
            flash('login_message', 'If the email exists in our system, a password reset link has been sent.', 'alert alert-success');
            redirect('account/login');
        } else {
            // Display forgot password form
            $data = [
                'title' => 'Forgot Password',
                'email' => '',
                'errors' => []
            ];
            
            $this->loadView('tourGuides/Accounts/forgot-password', $data);
        }
    }
    
    /**
     * Create user session
     * 
     * @param object $user The user object
     * @param bool $remember Whether to remember the user
     * @return void
     */
    private function createUserSession($user, $remember = false) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_type'] = $user->user_type;
        
        // Update last login time
        $this->userModel->updateLastLogin($user->id);
        
        // Set remember-me cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + (30 * 24 * 60 * 60); // 30 days
            
            // Store token in database
            $this->userModel->setRememberToken($user->id, $token, $expires);
            
            // Set cookie
            setcookie('remember_token', $token, $expires, '/', '', false, true);
        }
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