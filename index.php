<?php
/**
 * Tour Guide Website - Main Entry Point
 * Focusing on connecting users directly with tour guides
 */



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


require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';
require_once HELPER_PATH . '/functions.php';

$uri = $_SERVER['REQUEST_URI'];
$base = dirname($_SERVER['SCRIPT_NAME']);
if (strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}
if (preg_match('#^/guide/booking/(\d+)$#', $uri, $matches)) {
    require_once 'app/controllers/GuideController.php';
    $controller = new GuideController();
    $controller->bookingDetails($matches[1]);
    exit;
}
// Autoloader function for classes
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
    $modelFile = MODEL_PATH . '/' . $className . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }
    
    // Can add more class types here (e.g., libraries, etc.)
});

// Include necessary files
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';
require_once HELPER_PATH . '/functions.php';

// Start session
session_start();

// Parse the URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

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
