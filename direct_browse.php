<?php
/**
 * Direct access to the browse page without routing
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

// Include the controller
require_once CONTROLLER_PATH . '/TourGuideController.php';

// Create controller and call browse method
$controller = new TourGuideController();
$controller->browse();

echo "<hr>";
echo "<p><a href='/DACS/index.php'>Back to home</a></p>";
?> 