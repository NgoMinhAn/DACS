<?php
/**
 * Tour Guide Website - Main Entry Point
 * Focusing on connecting users directly with tour guides
 */

// Load Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load PHP configuration
require_once __DIR__ . '/app/config/php-config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define base path constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('HELPER_PATH', APP_PATH . '/helpers');

// Include necessary files first
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/config/google.php';
require_once HELPER_PATH . '/functions.php';


// Register autoloader
spl_autoload_register(function($className) {
    // Check for Controller class
    if (substr($className, -10) === 'Controller') {
        $file = CONTROLLER_PATH . '/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Check for Model class
    if (substr($className, -5) === 'Model') {
        $file = MODEL_PATH . '/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Start session
session_start();

// Get the request URI
$uri = $_SERVER['REQUEST_URI'];
$base = dirname($_SERVER['SCRIPT_NAME']);
if (strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}

// Remove query string if present
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

// Remove trailing slash
$uri = rtrim($uri, '/');

// Debug information
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Base path: " . $base);
error_log("Processed URI: " . $uri);

require_once APP_PATH . '/route.php';
handle_custom_routes(trim($uri, '/'), $routes);
// Handle guide/reviews route specifically
if ($uri === '/guide/reviews' || $uri === 'guide/reviews') {
    require_once CONTROLLER_PATH . '/GuideController.php';
    $controller = new GuideController();
    $controller->reviewsList();
    exit;
}

// Parse the URL
$url = explode('/', trim($uri, '/'));

// Special URL handling for static pages
if (!empty($url[0])) {
    $static_pages = ['about', 'contact', 'terms', 'privacy', 'careers'];
    
    if (in_array($url[0], $static_pages)) {
        // Route static pages to PageController
        require_once CONTROLLER_PATH . '/PageController.php';
        $pageController = new PageController();
        
        // Call the corresponding method if it exists
        $method = $url[0];
        if (method_exists($pageController, $method)) {
            $pageController->$method();
        } else {
            // Default to index if method doesn't exist
            $pageController->index();
        }
        exit;
    }
}

// Special URL handling for static pages
if (!empty($url[0])) {
    $static_pages = ['about', 'contact', 'terms', 'privacy', 'careers'];
    
    if (in_array($url[0], $static_pages)) {
        // Route static pages to PageController
        require_once CONTROLLER_PATH . '/PageController.php';
        $pageController = new PageController();
        
        // Call the corresponding method if it exists
        $method = $url[0];
        if (method_exists($pageController, $method)) {
            $pageController->$method();
        } else {
            // Default to index if method doesn't exist
            $pageController->index();
        }
        exit;
    }
}

// Set the controller (default to tourGuide if none specified)
$controller = !empty($url[0]) ? $url[0] : 'tourGuide';
$controller = ucfirst($controller) . 'Controller';
$controllerFile = CONTROLLER_PATH . '/' . $controller . '.php';

// Set the method (default to index if none specified)
$method = isset($url[1]) ? $url[1] : 'index';

// Set parameters
$params = array_slice($url, 2);

// Check if controller exists
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Check if controller class exists
    if (class_exists($controller)) {
        $controllerObj = new $controller();
        
        // Check if method exists
        if (method_exists($controllerObj, $method)) {
            call_user_func_array([$controllerObj, $method], $params);
        } else {
            // Method not found - redirect to 404
            header('Location: /error/notFound');
        }
    } else {
        // Controller class not found - redirect to 404
        header('Location: /error/notFound');
    }
} else {
    // Controller file not found - redirect to 404
    header('Location: /error/notFound');
}
