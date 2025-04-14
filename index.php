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

// Include necessary files
require_once APP_PATH . '/config/config.php';
require_once HELPER_PATH . '/functions.php';

// Start session
session_start();

// Parse the URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Set the controller (default to tourGuide if none specified)
$controller = !empty($url[0]) ? $url[0] : 'tourGuide';
$controller = ucfirst($controller) . 'Controller';
$controllerFile = CONTROLLER_PATH . '/' . $controller . '.php';

// Set the method (default to index if none specified)
$method = isset($url[1]) ? $url[1] : 'index';

// Set parameters
$params = array_slice($url, 2);

// Debug info - remove in production
echo "<h2>Debugging Information</h2>";
echo "<pre>";
echo "URL Parameter: " . (isset($_GET['url']) ? $_GET['url'] : 'Not set') . "\n";
echo "Controller: $controller\n";
echo "Controller File: $controllerFile\n";
echo "File exists: " . (file_exists($controllerFile) ? 'Yes' : 'No') . "\n";
echo "Method: $method\n";
echo "</pre>";

// Check if controller exists
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Check if controller class exists
    if (class_exists($controller)) {
        $controllerObj = new $controller();
        
        // Check if method exists
        if (method_exists($controllerObj, $method)) {
            // Debug info - remove in production
            echo "<p>About to call method: {$controller}->{$method}()</p>";
            
            call_user_func_array([$controllerObj, $method], $params);
        } else {
            // Method not found - redirect to 404
            echo "<p>Method {$method} not found in controller {$controller}</p>";
            header('Location: /error/notFound');
        }
    } else {
        // Controller class not found - redirect to 404
        echo "<p>Controller class {$controller} not found</p>";
        header('Location: /error/notFound');
    }
} else {
    // Controller file not found - redirect to 404
    echo "<p>Controller file {$controllerFile} not found</p>";
    header('Location: /error/notFound');
}
