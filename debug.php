<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Print basic server information
echo "<h2>Server Information</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

// Test the routing mechanism
echo "<h2>Testing Routes</h2>";
echo "<p>Try these links:</p>";
echo "<ul>";
echo "<li><a href='/DACS/tourGuide/index'>Home Page</a></li>";
echo "<li><a href='/DACS/tourGuide/browse'>Browse Page</a></li>";
echo "</ul>";

// Check for important files
echo "<h2>File Checks</h2>";
echo "<p>Controller file exists: " . (file_exists(__DIR__ . '/app/controllers/TourGuideController.php') ? 'Yes' : 'No') . "</p>";
echo "<p>View file exists: " . (file_exists(__DIR__ . '/app/views/tourGuides/browse.php') ? 'Yes' : 'No') . "</p>";

// Check directory structure
echo "<h2>Directory Structure</h2>";
$viewsDir = __DIR__ . '/app/views';
echo "<p>Views directory: " . (is_dir($viewsDir) ? 'Exists' : 'Missing') . "</p>";
$tourGuidesDir = $viewsDir . '/tourGuides';
echo "<p>tourGuides directory: " . (is_dir($tourGuidesDir) ? 'Exists' : 'Missing') . "</p>";

if (is_dir($tourGuidesDir)) {
    echo "<p>Contents of tourGuides directory:</p><ul>";
    $files = scandir($tourGuidesDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$file</li>";
        }
    }
    echo "</ul>";
}

// Check the TourGuideController browse method
echo "<h2>Controller Method</h2>";
if (file_exists(__DIR__ . '/app/controllers/TourGuideController.php')) {
    include_once(__DIR__ . '/app/controllers/TourGuideController.php');
    echo "<p>TourGuideController class exists: " . (class_exists('TourGuideController') ? 'Yes' : 'No') . "</p>";
    
    if (class_exists('TourGuideController')) {
        echo "<p>browse method exists: " . (method_exists('TourGuideController', 'browse') ? 'Yes' : 'No') . "</p>";
    }
}
?> 