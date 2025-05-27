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
            // Sanitize POST data - replacing deprecated FILTER_SANITIZE_STRING
            $_POST = $this->sanitizeInputArray($_POST);
            
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
            // Sanitize POST data
            $_POST = $this->sanitizeInputArray($_POST);
            
            // Process form
            $data = [
                'title' => 'Register Account',
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'terms' => isset($_POST['terms']) ? 1 : 0,
                'errors' => []
            ];
            
            // Validate name
            if (empty($data['name'])) {
                $data['errors']['name'] = 'Please enter your full name';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $data['errors']['email'] = 'Please enter your email address';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['errors']['email'] = 'Please enter a valid email address';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['errors']['email'] = 'Email is already registered';
            }
            
            // Validate password
            if (empty($data['password'])) {
                $data['errors']['password'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 6) {
                $data['errors']['password'] = 'Password must be at least 6 characters';
            }
            
            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['errors']['confirm_password'] = 'Please confirm your password';
            } elseif ($data['password'] !== $data['confirm_password']) {
                $data['errors']['confirm_password'] = 'Passwords do not match';
            }
            
            // Validate terms
            if (!$data['terms']) {
                $data['errors']['terms'] = 'You must agree to the Terms of Service';
            }
            
            // If no errors, register user
            if (empty($data['errors'])) {
                // Register user
                $userData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'user_type' => 'user',
                    'status' => 'active' // For now, no email verification
                ];
                
                $userId = $this->userModel->register($userData);
                
                if ($userId) {
                    // Registration success
                    flash('login_message', 'Registration successful! You can now log in.', 'alert alert-success');
                    redirect('account/login');
                } else {
                    // Registration failed
                    flash('register_message', 'Something went wrong. Please try again.', 'alert alert-danger');
                    $this->loadView('tourGuides/Accounts/register', $data);
                }
            } else {
                // Load view with errors
                $this->loadView('tourGuides/Accounts/register', $data);
            }
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
            // Sanitize POST data
            $_POST = $this->sanitizeInputArray($_POST);
            
            // Process form
            $email = trim($_POST['email']);
            
            // Validate email
            if (empty($email)) {
                $data = [
                    'title' => 'Forgot Password',
                    'email' => '',
                    'errors' => ['email' => 'Please enter your email address']
                ];
                $this->loadView('tourGuides/Accounts/forgot-password', $data);
                return;
            }
            
            // Check if email exists and create token
            $token = $this->userModel->createPasswordResetToken($email);
            
            // Always show success message even if email doesn't exist (security)
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
     * Sanitize input array
     * 
     * @param array $data The input array to sanitize
     * @return array The sanitized array
     */
    private function sanitizeInputArray($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeInputArray($value);
            } else {
                $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        return $sanitized;
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

    /**
     * Account Settings
     */
    public function settings($section = 'general') {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('account/login');
        }

        // Get user data
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            redirect('account/login');
        }

        // Redirect if not a regular user
        if ($user->user_type !== 'user') {
            redirect('');
        }

        // Handle different sections
        switch ($section) {
            case 'profile':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Process profile update
                    $data = [
                        'name' => trim($_POST['name']),
                        'email' => trim($_POST['email']),
                        'phone' => trim($_POST['phone']),
                        'address' => trim($_POST['address']),
                        'errors' => []
                    ];

                    // Validate name
                    if (empty($data['name'])) {
                        $data['errors']['name'] = 'Please enter your name';
                    }

                    // Validate email
                    if (empty($data['email'])) {
                        $data['errors']['email'] = 'Please enter your email';
                    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $data['errors']['email'] = 'Please enter a valid email';
                    } elseif ($this->userModel->findUserByEmailExcept($data['email'], $userId)) {
                        $data['errors']['email'] = 'Email is already taken';
                    }

                    // Handle profile image upload
                    if (!empty($_FILES['avatar']['name'])) {
                        $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/avatars/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $fileExtension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                        $allowedTypes = ['jpg', 'jpeg', 'png'];

                        if (!in_array($fileExtension, $allowedTypes)) {
                            $data['errors']['avatar'] = 'Only JPG, JPEG & PNG files are allowed';
                        } elseif ($_FILES['avatar']['size'] > 5000000) { // 5MB
                            $data['errors']['avatar'] = 'File size must not exceed 5MB';
                        } else {
                            $fileName = uniqid('avatar_') . '.' . $fileExtension;
                            $targetPath = $uploadDir . $fileName;

                            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                                // Delete old avatar if exists
                                if ($user->profile_image && $user->profile_image !== 'default.jpg') {
                                    $oldAvatar = $uploadDir . $user->profile_image;
                                    if (file_exists($oldAvatar)) {
                                        unlink($oldAvatar);
                                    }
                                }
                                $data['profile_image'] = $fileName;
                            } else {
                                $data['errors']['avatar'] = 'Error uploading file';
                            }
                        }
                    }

                    if (empty($data['errors'])) {
                        // Prepare data for update
                        $updateData = [
                            'user_id' => $userId,
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'phone' => $data['phone'] ?? '',
                            'address' => $data['address'] ?? ''
                        ];
                        
                        // Add profile image if uploaded
                        if (isset($data['profile_image'])) {
                            $updateData['avatar'] = $data['profile_image'];
                        }

                        // Update profile
                        if ($this->userModel->updateProfile($updateData)) {
                            // Update session
                            $_SESSION['user_name'] = $data['name'];
                            $_SESSION['user_email'] = $data['email'];
                            if (isset($data['profile_image'])) {
                                $_SESSION['user_image'] = $data['profile_image'];
                            }

                            flash('settings_message', 'Profile updated successfully!', 'alert alert-success');
                        } else {
                            flash('settings_message', 'Error updating profile', 'alert alert-danger');
                        }
                        redirect('account/settings/profile');
                    } else {
                        // Load view with errors
                        $data['user'] = $user;
                        $data['title'] = 'Profile Settings';
                        $data['section'] = 'profile';
                        $this->loadView('user/accountsetting/profile', $data);
                    }
                } else {
                    $data = [
                        'title' => 'Profile Settings',
                        'user' => $user,
                        'section' => 'profile'
                    ];
                    $this->loadView('user/accountsetting/profile', $data);
                }
                break;

            case 'password':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $data = [
                        'current_password' => trim($_POST['current_password']),
                        'new_password' => trim($_POST['new_password']),
                        'confirm_password' => trim($_POST['confirm_password']),
                        'errors' => []
                    ];

                    // Validate current password
                    if (empty($data['current_password'])) {
                        $data['errors']['current_password'] = 'Please enter current password';
                    } elseif (!$this->userModel->verifyPassword($userId, $data['current_password'])) {
                        $data['errors']['current_password'] = 'Current password is incorrect';
                    }

                    // Validate new password
                    if (empty($data['new_password'])) {
                        $data['errors']['new_password'] = 'Please enter new password';
                    } elseif (strlen($data['new_password']) < 6) {
                        $data['errors']['new_password'] = 'Password must be at least 6 characters';
                    }

                    // Validate confirm password
                    if (empty($data['confirm_password'])) {
                        $data['errors']['confirm_password'] = 'Please confirm new password';
                    } elseif ($data['new_password'] !== $data['confirm_password']) {
                        $data['errors']['confirm_password'] = 'Passwords do not match';
                    }

                    if (empty($data['errors'])) {
                        if ($this->userModel->updatePassword($userId, $data['new_password'])) {
                            flash('settings_message', 'Password updated successfully!', 'alert alert-success');
                        } else {
                            flash('settings_message', 'Error updating password', 'alert alert-danger');
                        }
                        redirect('account/settings/password');
                    } else {
                        $data['user'] = $user;
                        $data['title'] = 'Change Password';
                        $data['section'] = 'password';
                        $this->loadView('user/accountsetting/password', $data);
                    }
                } else {
                    $data = [
                        'title' => 'Change Password',
                        'user' => $user,
                        'section' => 'password'
                    ];
                    $this->loadView('user/accountsetting/password', $data);
                }
                break;

            default:
                $data = [
                    'title' => 'Account Settings',
                    'user' => $user,
                    'section' => 'general'
                ];
                $this->loadView('user/accountsetting/settings', $data);
                break;
        }
    }

    /**
     * Delete Account
     */
    public function delete() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('account/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = $this->sanitizeInputArray($_POST);

            $userId = $_SESSION['user_id'];
            $password = trim($_POST['password']);

            // Verify password
            if (empty($password)) {
                flash('settings_message', 'Please enter your password to confirm account deletion.', 'alert alert-danger');
                redirect('account/settings');
            }

            if (!$this->userModel->verifyPassword($userId, $password)) {
                flash('settings_message', 'Incorrect password. Account deletion cancelled.', 'alert alert-danger');
                redirect('account/settings');
            }

            // Delete account
            if ($this->userModel->deleteAccount($userId)) {
                // Destroy session
                unset($_SESSION['user_id']);
                unset($_SESSION['user_name']);
                unset($_SESSION['user_email']);
                unset($_SESSION['user_type']);
                session_destroy();

                flash('login_message', 'Your account has been successfully deleted.', 'alert alert-success');
                redirect('account/login');
            } else {
                flash('settings_message', 'An error occurred while deleting your account. Please try again.', 'alert alert-danger');
                redirect('account/settings');
            }
        } else {
            redirect('account/settings');
        }
    }

    /**
     * Become a Guide (user application)
     */
    public function becomeGuide() {
        if (!isLoggedIn()) {
            redirect('account/login');
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $application = $this->userModel->getGuideApplicationByUserId($user->id);
        if ($application && $application->status == 'pending') {
            flash('settings_message', 'Your application is pending admin approval.');
            redirect('account/settings');
        }
        if ($application && $application->status == 'approved') {
            flash('settings_message', 'You are already a guide.');
            redirect('account/settings');
        }

        require_once dirname(__DIR__) . '/models/GuideModel.php';
        $guideModel = new GuideModel();
        $specialties = $guideModel->getAllSpecialties();
        $languages = $guideModel->getAllLanguages();

        $step = isset($_GET['step']) ? $_GET['step'] : '1';

        if ($step === '1') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Handle avatar upload
                $profile_image = null;
                if (!empty($_FILES['profile_image']['name'])) {
                    $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/avatars/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileExtension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                    $allowedTypes = ['jpg', 'jpeg', 'png'];
                    if (in_array($fileExtension, $allowedTypes) && $_FILES['profile_image']['size'] <= 5000000) {
                        $fileName = uniqid('guide_') . '.' . $fileExtension;
                        $targetPath = $uploadDir . $fileName;
                        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
                            $profile_image = $fileName;
                        }
                    }
                }
                // Lưu tạm vào session
                $_SESSION['become_guide'] = [
                    'location' => trim($_POST['location']),
                    'phone' => trim($_POST['phone']),
                    'certifications' => trim($_POST['certifications']),
                    'profile_image' => $profile_image,
                    'bio' => trim($_POST['bio']),
                    'experience' => trim($_POST['experience'])
                ];
                // Chuyển sang bước 2
                redirect('account/becomeguide?step=2');
            } else {
                $data = [
                    'user' => $user
                ];
                $this->loadView('user/accountsetting/become-guide-step1', $data);
            }
        } else if ($step === '2') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $hourly_rate = floatval($_POST['hourly_rate']);
                $daily_rate = floatval($_POST['daily_rate']);
                $specialties_selected = isset($_POST['specialties']) ? implode(',', $_POST['specialties']) : '';
                $languages_selected = isset($_POST['languages']) ? implode(',', $_POST['languages']) : '';
                $languages_fluency = isset($_POST['languages_fluency']) ? implode(',', $_POST['languages_fluency']) : '';
                // Lấy dữ liệu từ session
                $info = $_SESSION['become_guide'] ?? [];
                $this->userModel->createGuideApplication(
                    $user->id,
                    $specialties_selected,
                    $info['bio'] ?? '',
                    $info['experience'] ?? '',
                    $info['location'] ?? '',
                    $info['phone'] ?? '',
                    $info['certifications'] ?? '',
                    $info['profile_image'] ?? null,
                    $hourly_rate,
                    $daily_rate,
                    $languages_selected,
                    $languages_fluency
                );
                unset($_SESSION['become_guide']);
                flash('settings_message', 'Your application has been submitted and is pending admin approval.');
                redirect('account/settings');
            } else {
                $data = [
                    'user' => $user,
                    'specialties' => $specialties,
                    'languages' => $languages
                ];
                $this->loadView('user/accountsetting/become-guide-step2', $data);
            }
        }
    }
} 