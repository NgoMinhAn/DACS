<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Route Test</h1>";

// Print request info
echo "<h2>Request Information</h2>";
echo "<p>REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>QUERY_STRING: " . $_SERVER['QUERY_STRING'] . "</p>";
echo "<p>URL parameter: " . (isset($_GET['url']) ? $_GET['url'] : 'Not found') . "</p>";

// Test URL parsing like in index.php
echo "<h2>URL Parsing Test</h2>";
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

echo "<p>Parsed URL:</p>";
echo "<pre>";
print_r($url);
echo "</pre>";

echo "<p>Controller would be: " . (!empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'Default') . "</p>";
echo "<p>Method would be: " . (isset($url[1]) ? $url[1] : 'index') . "</p>";
echo "<p>Parameters would be: </p>";
echo "<pre>";
print_r(array_slice($url, 2));
echo "</pre>";

// Provide links for testing
echo "<h2>Test Links</h2>";
echo "<p><a href='/DACS/test_route.php?url=tourGuide/browse'>Test tourGuide/browse</a></p>";
echo "<p><a href='/DACS/tourGuide/browse'>Real tourGuide/browse</a></p>";
?> 