<?php
/**
 * Account Controller
 * Handles user authentication and account management
 */

use Google\Client as GoogleClient;
use Google\Service\Oauth2;

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
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            // Init data
            $data = [
                'title' => 'Login',
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }
            
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }
            
            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                // User not found
                $data['email_err'] = 'No user found';
            }
            
            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                    
                    // Check if there's a redirect URL stored in session
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirect = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']); // Clear the stored URL
                        redirect($redirect);
                    } else {
                        // Default redirect based on user type
                        if ($loggedInUser->user_type === 'guide') {
                            redirect('guide/dashboard');
                        } else if ($loggedInUser->user_type === 'admin') {
                            redirect('admin/dashboard');
                        } else {
                            redirect('');
                        }
                    }
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->loadView('tourGuides/Accounts/login', $data);
                }
            } else {
                // Load view with errors
                $this->loadView('tourGuides/Accounts/login', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Login',
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Load view
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
                    'title' => 'Quên mật khẩu',
                    'email' => '',
                    'errors' => ['email' => 'Vui lòng nhập địa chỉ email của bạn']
                ];
                $this->loadView('tourGuides/Accounts/forgot-password', $data);
                return;
            }
            
            // Check if email exists
            $user = $this->userModel->findUserByEmail($email);
            
            if ($user) {
                // Generate reset token
                $resetToken = bin2hex(random_bytes(32));
                // Extend expiration time to 24 hours
                $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
                
                // Save reset token
                if ($this->userModel->savePasswordResetToken($user->id, $resetToken, $expires)) {
                    // Send reset email
                    require_once HELPER_PATH . '/Mailer.php';
                    $mailer = new Mailer();
                    if ($mailer->sendPasswordReset($user->email, $user->name, $resetToken)) {
                        flash('login_message', 'Một liên kết đặt lại mật khẩu đã được gửi đến email của bạn. Liên kết có hiệu lực trong 24 giờ.', 'alert alert-success');
                    } else {
                        flash('login_message', 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.', 'alert alert-danger');
                    }
                } else {
                    flash('login_message', 'Có lỗi xảy ra. Vui lòng thử lại sau.', 'alert alert-danger');
                }
            } else {
                // Always show success message even if email doesn't exist (security)
                flash('login_message', 'Nếu email tồn tại trong hệ thống, một liên kết đặt lại mật khẩu đã được gửi.', 'alert alert-success');
            }
            redirect('account/login');
        } else {
            // Display forgot password form
            $data = [
                'title' => 'Quên mật khẩu',
                'email' => '',
                'errors' => []
            ];
            
            $this->loadView('tourGuides/Accounts/forgot-password', $data);
        }
    }
    
    /**
     * Reset password method
     */
    public function resetPassword($token = null) {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('');
        }
        
        // Check if token is provided
        if (!$token) {
            flash('login_message', 'Liên kết đặt lại mật khẩu không hợp lệ.', 'alert alert-danger');
            redirect('account/login');
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = $this->sanitizeInputArray($_POST);
            
            // Process form
            $data = [
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'errors' => []
            ];
            
            // Validate password
            if (empty($data['password'])) {
                $data['errors']['password'] = 'Vui lòng nhập mật khẩu mới';
            } elseif (strlen($data['password']) < 6) {
                $data['errors']['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['errors']['confirm_password'] = 'Vui lòng xác nhận mật khẩu';
            } elseif ($data['password'] !== $data['confirm_password']) {
                $data['errors']['confirm_password'] = 'Mật khẩu không khớp';
            }
            
            if (empty($data['errors'])) {
                // Verify token and update password
                if ($this->userModel->resetPassword($token, $data['password'])) {
                    flash('login_message', 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập ngay bây giờ.', 'alert alert-success');
                    redirect('account/login');
                } else {
                    flash('login_message', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.', 'alert alert-danger');
                    redirect('account/login');
                }
            } else {
                // Load view with errors
                $data['title'] = 'Đặt lại mật khẩu';
                $data['token'] = $token;
                $this->loadView('tourGuides/Accounts/reset-password', $data);
            }
        } else {
            // Verify token
            if ($this->userModel->verifyResetToken($token)) {
                // Display reset password form
                $data = [
                    'title' => 'Đặt lại mật khẩu',
                    'token' => $token,
                    'errors' => []
                ];
                
                $this->loadView('tourGuides/Accounts/reset-password', $data);
            } else {
                flash('login_message', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.', 'alert alert-danger');
                redirect('account/login');
            }
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
        
        // Send login notification email
        require_once HELPER_PATH . '/Mailer.php';
        $mailer = new Mailer();
        $loginTime = date('Y-m-d H:i:s');
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $mailer->sendLoginNotification($user->email, $user->name, $loginTime, $ipAddress);
        
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
     * Google Login
     * Initiates Google OAuth login process
     */
    public function googleLogin() {
        // Google OAuth Configuration
        $clientID = GOOGLE_CLIENT_ID;
        $clientSecret = GOOGLE_CLIENT_SECRET;
        $redirectUri = GOOGLE_REDIRECT_URI;

        // Create Google Client
        $client = new GoogleClient();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        // Get auth URL
        $authUrl = $client->createAuthUrl();

        // Redirect to Google
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * Google Callback
     * Handles the OAuth callback from Google
     */
    public function googleCallback() {
        // Google OAuth Configuration
        $clientID = GOOGLE_CLIENT_ID;
        $clientSecret = GOOGLE_CLIENT_SECRET;
        $redirectUri = GOOGLE_REDIRECT_URI;

        // Create Google Client
        $client = new GoogleClient();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);

        // Disable SSL verification in development environment
        if (ENVIRONMENT === 'development') {
            $client->setHttpClient(new \GuzzleHttp\Client([
                'verify' => false
            ]));
        }

        // Get token
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token);

            // Get user info
            $google_oauth = new Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            // Check if user exists
            $user = $this->userModel->findUserByEmail($google_account_info->email);

            if ($user) {
                // User exists - log them in
                $this->createUserSession($user);
                
                // Redirect based on user type
                switch ($user->user_type) {
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
                // Create new user
                $userData = [
                    'name' => $google_account_info->name,
                    'email' => $google_account_info->email,
                    'password' => bin2hex(random_bytes(16)), // Random password
                    'user_type' => 'user',
                    'status' => 'active',
                    'google_id' => $google_account_info->id
                ];

                $userId = $this->userModel->register($userData);

                if ($userId) {
                    $user = $this->userModel->findUserById($userId);
                    $this->createUserSession($user);
                    redirect('');
                } else {
                    flash('login_message', 'Error creating account', 'alert alert-danger');
                    redirect('account/login');
                }
            }
        } else {
            flash('login_message', 'Error authenticating with Google', 'alert alert-danger');
            redirect('account/login');
        }
    }

    public function becomeguide() {
        $step = isset($_GET['step']) ? intval($_GET['step']) : 1;

        if ($step === 1) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $_SESSION['become_guide_step1'] = $_POST;
                redirect('account/becomeguide?step=2');
            }
            $this->loadView('user/accountsetting/become-guide-step1');
        } elseif ($step === 2) {
            $specialties = $this->userModel->getAllSpecialties();
            $languages = $this->userModel->getAllLanguages();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Lấy dữ liệu từ session bước 1 và POST bước 2
                $step1 = isset($_SESSION['become_guide_step1']) ? $_SESSION['become_guide_step1'] : [];
                $step2 = $_POST;
                $userId = $_SESSION['user_id'];

                // Xử lý specialties và languages (lưu dạng chuỗi, có thể tùy chỉnh)
                $specialtiesStr = isset($step2['specialties']) ? implode(',', $step2['specialties']) : '';
                $languagesStr = isset($step2['languages']) ? implode(',', $step2['languages']) : '';

                // Lưu vào bảng guide_applications
                $db = new Database();
                $db->query('INSERT INTO guide_applications (user_id, specialty, bio, experience, status, location, phone, certifications, profile_image, hourly_rate, daily_rate, created_at) VALUES (:user_id, :specialty, :bio, :experience, :status, :location, :phone, :certifications, :profile_image, :hourly_rate, :daily_rate, NOW())');
                $db->bind(':user_id', $userId);
                $db->bind(':specialty', $specialtiesStr);
                $db->bind(':bio', $step1['bio'] ?? '');
                $db->bind(':experience', $step1['experience'] ?? '');
                $db->bind(':status', 'pending');
                $db->bind(':location', $step1['location'] ?? '');
                $db->bind(':phone', $step1['phone'] ?? '');
                $db->bind(':certifications', $step1['certifications'] ?? '');
                $db->bind(':profile_image', isset($step1['profile_image']) ? $step1['profile_image'] : null);
                $db->bind(':hourly_rate', $step2['hourly_rate'] ?? 0);
                $db->bind(':daily_rate', $step2['daily_rate'] ?? 0);
                $db->execute();

                unset($_SESSION['become_guide_step1']);
                unset($_SESSION['become_guide_step2']);
                flash('settings_message', 'Your application has been submitted and is pending admin approval!', 'alert alert-success');
                redirect('account/settings');
            }
            $this->loadView('user/accountsetting/become-guide-step2', [
                'specialties' => $specialties,
                'languages' => $languages
            ]);
        }
    }
} 