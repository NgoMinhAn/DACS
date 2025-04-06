<?php
/**
 * Freelance Tour Guide Website
 * Main Entry Point
 */

// Start session
session_start();

// Define constants for paths
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', APP_PATH . '/config');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');
define('VIEWS_PATH', APP_PATH . '/views');
define('HELPERS_PATH', APP_PATH . '/helpers');

// For development - show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load database configuration
require_once CONFIG_PATH . '/database.php';

// Autoload function for classes
spl_autoload_register(function ($class) {
    // Check if the class is a controller
    if (file_exists(CONTROLLERS_PATH . "/{$class}.php")) {
        require_once CONTROLLERS_PATH . "/{$class}.php";
        return;
    }
    
    // Check if the class is a model
    if (file_exists(MODELS_PATH . "/{$class}.php")) {
        // Make sure database config is loaded before loading models
        require_once CONFIG_PATH . '/database.php';
        require_once MODELS_PATH . "/{$class}.php";
        return;
    }
    
    // Check if the class is a helper
    if (file_exists(HELPERS_PATH . "/{$class}.php")) {
        require_once HELPERS_PATH . "/{$class}.php";
        return;
    }
});

// Define routes
$routes = [
    // Static routes
    '/' => ['controller' => 'HomeController', 'action' => 'index'],
    '/about' => ['controller' => 'HomeController', 'action' => 'about'],
    '/contact' => ['controller' => 'HomeController', 'action' => 'contact'],
    '/terms' => ['controller' => 'HomeController', 'action' => 'terms'],
    '/privacy' => ['controller' => 'HomeController', 'action' => 'privacy'],
    '/cookies' => ['controller' => 'HomeController', 'action' => 'cookies'],
    '/faq' => ['controller' => 'HomeController', 'action' => 'faq'],
    
    // User routes
    '/login' => ['controller' => 'UserController', 'action' => 'login'],
    '/login/process' => ['controller' => 'UserController', 'action' => 'loginPost'],
    '/register' => ['controller' => 'UserController', 'action' => 'register'],
    '/register/process' => ['controller' => 'UserController', 'action' => 'registerPost'],
    '/logout' => ['controller' => 'UserController', 'action' => 'logout'],
    '/profile' => ['controller' => 'UserController', 'action' => 'profile'],
    '/profile/edit' => ['controller' => 'UserController', 'action' => 'editProfile'],
    '/profile/update' => ['controller' => 'UserController', 'action' => 'updateProfile'],
    
    // Guide routes
    '/guides' => ['controller' => 'GuideController', 'action' => 'index'],
    '/guides/create' => ['controller' => 'GuideController', 'action' => 'create'],
    '/guides/store' => ['controller' => 'GuideController', 'action' => 'store'],
    '/guides/profile' => ['controller' => 'GuideController', 'action' => 'profile'],
    '/guides/edit' => ['controller' => 'GuideController', 'action' => 'edit'],
    '/guides/update' => ['controller' => 'GuideController', 'action' => 'update'],
    
    // Booking routes
    '/bookings' => ['controller' => 'BookingController', 'action' => 'index'],
    '/bookings/store' => ['controller' => 'BookingController', 'action' => 'store'],
    '/bookings/payment/process' => ['controller' => 'BookingController', 'action' => 'processPayment'],
    
    // Review routes
    '/reviews/store' => ['controller' => 'ReviewController', 'action' => 'store'],
    '/reviews/update' => ['controller' => 'ReviewController', 'action' => 'update'],
    '/reviews/user' => ['controller' => 'ReviewController', 'action' => 'showUserReviews'],
];

// Dynamic routes with parameters
$dynamicRoutes = [
    '/guides/show/(\d+)' => ['controller' => 'GuideController', 'action' => 'show'],
    '/bookings/create/(\d+)' => ['controller' => 'BookingController', 'action' => 'create'],
    '/bookings/show/(\d+)' => ['controller' => 'BookingController', 'action' => 'show'],
    '/bookings/payment/(\d+)' => ['controller' => 'BookingController', 'action' => 'payment'],
    '/bookings/confirm/(\d+)' => ['controller' => 'BookingController', 'action' => 'confirm'],
    '/bookings/cancel/(\d+)' => ['controller' => 'BookingController', 'action' => 'cancel'],
    '/reviews/create/(\d+)' => ['controller' => 'ReviewController', 'action' => 'create'],
    '/reviews/edit/(\d+)' => ['controller' => 'ReviewController', 'action' => 'edit'],
    '/reviews/delete/(\d+)' => ['controller' => 'ReviewController', 'action' => 'delete'],
    '/reviews/guide/(\d+)' => ['controller' => 'ReviewController', 'action' => 'showGuideReviews'],
    '/reviews/user/(\d+)' => ['controller' => 'ReviewController', 'action' => 'showUserReviews'],
];

// Router function
function route($path) {
    // Remove query string if present
    $path = strtok($path, '?');
    
    // Remove leading slash and base path if present
    $basePath = '/DACS';
    if (strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }
    
    // Ensure path starts with '/'
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
    
    global $routes, $dynamicRoutes;
    
    // Check if route exists in static routes
    if (isset($routes[$path])) {
        $route = $routes[$path];
        $controller = $route['controller'];
        $action = $route['action'];
        $params = [];
    } else {
        // Check for dynamic routes
        $matched = false;
        foreach ($dynamicRoutes as $pattern => $route) {
            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                $controller = $route['controller'];
                $action = $route['action'];
                // Remove the full match from the params
                array_shift($matches);
                $params = $matches;
                $matched = true;
                break;
            }
        }
        
        // If no route matched, show 404
        if (!$matched) {
            header("HTTP/1.0 404 Not Found");
            include VIEWS_PATH . '/shares/404.php';
            exit;
        }
    }
    
    // Load controller
    if (file_exists(CONTROLLERS_PATH . '/' . $controller . '.php')) {
        require_once CONTROLLERS_PATH . '/' . $controller . '.php';
        
        // Create controller instance
        $controller_instance = new $controller();
        
        // Call action with parameters
        call_user_func_array([$controller_instance, $action], $params);
    } else {
        header("HTTP/1.0 404 Not Found");
        include VIEWS_PATH . '/shares/404.php';
        exit;
    }
}

// Create a HomeController if it doesn't exist
if (!file_exists(CONTROLLERS_PATH . '/HomeController.php')) {
    // Create a basic HomeController
    $home_controller = '<?php

class HomeController {
    public function index() {
        // Load header
        require_once VIEWS_PATH . \'/shares/header.php\';
        
        // Load home view
        require_once VIEWS_PATH . \'/home/index.php\';
        
        // Load footer
        require_once VIEWS_PATH . \'/shares/footer.php\';
    }
    
    public function about() {
        // Load header
        require_once VIEWS_PATH . \'/shares/header.php\';
        
        // Load about view
        require_once VIEWS_PATH . \'/home/about.php\';
        
        // Load footer
        require_once VIEWS_PATH . \'/shares/footer.php\';
    }
    
    public function contact() {
        // Load header
        require_once VIEWS_PATH . \'/shares/header.php\';
        
        // Load contact view
        require_once VIEWS_PATH . \'/home/contact.php\';
        
        // Load footer
        require_once VIEWS_PATH . \'/shares/footer.php\';
    }
    
    public function terms() {
        // Load header
        require_once VIEWS_PATH . \'/shares/header.php\';
        
        // Load terms view
        require_once VIEWS_PATH . \'/home/terms.php\';
        
        // Load footer
        require_once VIEWS_PATH . \'/shares/footer.php\';
    }
    
    public function privacy() {
        // Load header
        require_once VIEWS_PATH . \'/shares/header.php\';
        
        // Load privacy view
        require_once VIEWS_PATH . \'/home/privacy.php\';
        
        // Load footer
        require_once VIEWS_PATH . \'/shares/footer.php\';
    }
    
    public function cookies() {
        // Load header
        require_once VIEWS_PATH . \'/shares/header.php\';
        
        // Load cookies view
        require_once VIEWS_PATH . \'/home/cookies.php\';
        
        // Load footer
        require_once VIEWS_PATH . \'/shares/footer.php\';
    }
    
    public function faq() {
        // Load header
        require_once VIEWS_PATH . \'/shares/header.php\';
        
        // Load faq view
        require_once VIEWS_PATH . \'/home/faq.php\';
        
        // Load footer
        require_once VIEWS_PATH . \'/shares/footer.php\';
    }
}';

    // Write the HomeController file
    file_put_contents(CONTROLLERS_PATH . '/HomeController.php', $home_controller);
}

// Create a 404 page if it doesn't exist
if (!file_exists(VIEWS_PATH . '/shares/404.php')) {
    $page_404 = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .error-template {
            padding: 40px 15px;
            text-align: center;
        }
        .error-actions {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .error-actions .btn {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>Oops!</h1>
                    <h2>404 Not Found</h2>
                    <div class="error-details mb-4">
                        Sorry, the page you requested could not be found.
                    </div>
                    <div class="error-actions">
                        <a href="/" class="btn btn-primary">
                            <i class="fas fa-home"></i> Take Me Home
                        </a>
                        <a href="/contact" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>';

    // Create directories if they don't exist
    if (!file_exists(VIEWS_PATH . '/shares')) {
        mkdir(VIEWS_PATH . '/shares', 0777, true);
    }

    // Write the 404 page file
    file_put_contents(VIEWS_PATH . '/shares/404.php', $page_404);
}

// Create home directory and index view if it doesn't exist
if (!file_exists(VIEWS_PATH . '/home/index.php')) {
    // Create directory if it doesn't exist
    if (!file_exists(VIEWS_PATH . '/home')) {
        mkdir(VIEWS_PATH . '/home', 0777, true);
    }
    
    // Create a basic index view
    $index_view = '<div class="hero-section">
    <div class="container hero-content">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-4 mb-4">Discover Amazing Places with Local Guides</h1>
                <p class="lead mb-4">Connect with experienced tour guides who know the best spots and stories in your destination.</p>
                <a href="/guides" class="btn btn-primary btn-lg mr-3">Find a Guide</a>
                <a href="/guides/create" class="btn btn-outline-light btn-lg">Become a Guide</a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4 text-center">
            <div class="p-4">
                <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                <h3>Find Guides</h3>
                <p>Search for qualified tour guides based on location, language, and specialities.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4 text-center">
            <div class="p-4">
                <i class="fas fa-calendar-check fa-3x mb-3 text-primary"></i>
                <h3>Book Tours</h3>
                <p>Schedule your perfect tour with just a few clicks and secure online payments.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4 text-center">
            <div class="p-4">
                <i class="fas fa-star fa-3x mb-3 text-primary"></i>
                <h3>Share Experiences</h3>
                <p>Write reviews and share your amazing tour experiences with others.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5 mt-5">
    <div class="container">
        <h2 class="text-center mb-5">Featured Tour Guides</h2>
        <div class="row">
            <!-- This section would be dynamically populated with actual guides from the database -->
            <div class="col-md-4">
                <div class="card guide-card">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Tour Guide">
                    <div class="card-body">
                        <h5 class="card-title">John Doe</h5>
                        <p class="card-text text-muted">Speciality: Historical Tours</p>
                        <div class="rating mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="ml-1">4.5 (120 reviews)</span>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card guide-card">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Tour Guide">
                    <div class="card-body">
                        <h5 class="card-title">Jane Smith</h5>
                        <p class="card-text text-muted">Speciality: Food Tours</p>
                        <div class="rating mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span class="ml-1">5.0 (87 reviews)</span>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card guide-card">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Tour Guide">
                    <div class="card-body">
                        <h5 class="card-title">Michael Johnson</h5>
                        <p class="card-text text-muted">Speciality: Adventure Tours</p>
                        <div class="rating mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span class="ml-1">4.0 (65 reviews)</span>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="/guides" class="btn btn-primary">Browse All Guides</a>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h2 class="mb-4">Why Choose Our Platform?</h2>
            <ul class="list-unstyled">
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Verified and experienced local guides</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Customizable tour experiences</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Secure booking and payment system</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Transparent pricing with no hidden fees</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> 24/7 customer support</li>
            </ul>
            <a href="/about" class="btn btn-outline-primary mt-3">Learn More</a>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Become a Tour Guide</h3>
                    <p class="card-text">Share your knowledge and passion for your city or region. Set your own schedule and prices.</p>
                    <ul>
                        <li>Create your profile and showcase your expertise</li>
                        <li>Set your availability and tour offerings</li>
                        <li>Earn money doing what you love</li>
                    </ul>
                    <a href="/guides/create" class="btn btn-primary">Sign Up as Guide</a>
                </div>
            </div>
        </div>
    </div>
</div>';
    
    // Write the index view file
    file_put_contents(VIEWS_PATH . '/home/index.php', $index_view);
}

// Get the requested URL
$request_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

// Route the request
route($request_url); 