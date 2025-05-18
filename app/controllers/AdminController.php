<?php
/**
 * Admin Controller
 * Handles all admin dashboard functions
 */
class AdminController {
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || $_SESSION['user_type'] !== 'admin') {
            redirect('account/login');
        }
        
        // Load the user model
        $this->userModel = new UserModel();
    }
    
    /**
     * Admin dashboard
     */
    public function dashboard() {
        // Get counts for dashboard
        $userCount = $this->userModel->getUserCount();
        $guideCount = $this->userModel->getGuideCount();
        
        $data = [
            'title' => 'Admin Dashboard',
            'userCount' => $userCount,
            'guideCount' => $guideCount
        ];
        
        $this->loadView('admin/dashboard', $data);
    }
    
    /**
     * List all users
     */
    public function users() {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        $this->loadView('admin/users', ['users' => $users]);
    }
    /**
     * List all guides
     */
    public function guides() {
        // Get all guides
        $guides = $this->userModel->getAllGuides();
        
        $data = [
            'title' => 'Manage Guides',
            'guides' => $guides
        ];
        
        $this->loadView('admin/guides', $data);
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
        
        // Load the view - create a temporary fallback if view doesn't exist
        $viewPath = VIEW_PATH . '/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Display a temporary view
            echo '<div class="container mt-4">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white">';
            echo '<h2>' . $title . '</h2>';
            echo '</div>';
            echo '<div class="card-body">';
            
            if (isset($userCount) && isset($guideCount)) {
                echo '<div class="row">';
                echo '<div class="col-md-6">';
                echo '<div class="card bg-light mb-3">';
                echo '<div class="card-body text-center">';
                echo '<h5 class="card-title">Total Users</h5>';
                echo '<p class="display-4">' . $userCount . '</p>';
                echo '</div></div></div>';
                
                echo '<div class="col-md-6">';
                echo '<div class="card bg-light mb-3">';
                echo '<div class="card-body text-center">';
                echo '<h5 class="card-title">Total Guides</h5>';
                echo '<p class="display-4">' . $guideCount . '</p>';
                echo '</div></div></div>';
                echo '</div>';
                
                echo '<div class="alert alert-info">';
                echo 'Welcome to the admin dashboard. This is a temporary view until you create proper admin views.';
                echo '</div>';
                
                echo '<div class="list-group mt-4">';
                echo '<a href="' . url('admin/users') . '" class="list-group-item list-group-item-action">Manage Users</a>';
                echo '<a href="' . url('admin/guides') . '" class="list-group-item list-group-item-action">Manage Guides</a>';
                echo '</div>';
            } else if (isset($users)) {
                echo '<p>Here you can manage users. This is a placeholder for the users management page.</p>';
                echo '<a href="' . url('admin/dashboard') . '" class="btn btn-primary">Back to Dashboard</a>';
            } else if (isset($guides)) {
                echo '<p>Here you can manage guides. This is a placeholder for the guides management page.</p>';
                echo '<a href="' . url('admin/dashboard') . '" class="btn btn-primary">Back to Dashboard</a>';
            }
            
            echo '</div></div></div>';
        }
        
        // Load footer
        require_once VIEW_PATH . '/shares/footer.php';
    }

        public function editUser($id) {
        $userModel = new UserModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $userModel->updateUser($id, $name, $email);
            redirect('admin/users');
        }
        $user = $userModel->getUserById($id);
        $this->loadView('admin/editUser', ['user' => $user]);
    }
        
    public function deleteUser($id) {
        $userModel = new UserModel();
        $userModel->deleteUser($id);
        redirect('admin/users');
    }
} 