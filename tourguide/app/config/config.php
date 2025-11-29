<?php
/**
 * Site Configuration
 */

// Set application environment
define('ENVIRONMENT', 'development'); // Options: development, production

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'TourGuide'); // Updated from tour_guide_db to TourGuide

// URL Root
// Dynamically detect base URL to support running from a subfolder (e.g., /tourguide)
// and different hosts (localhost, custom vhost like tourguide.dacn)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
define('URL_ROOT', $scheme . '://' . $host . ($scriptDir === '' ? '' : $scriptDir));

// Site Name
define('SITE_NAME', 'Tour Guide Connect');

// App Version
define('APP_VERSION', '1.0.0');

// Realtime websocket configuration (update secrets in production!)
define('REALTIME_SERVER_URL', getenv('REALTIME_SERVER_URL') ?: 'http://127.0.0.1:3001');
define('REALTIME_AUTH_SECRET', 'e6f1c4173a224b6aa3f6eab8c8910f3d45e9a6f74f3f4fd9d8b41b2bb8e7c1dd');
define('REALTIME_BROADCAST_TOKEN', 'f3bd869c9c3945cf8cbab3b2f1da6d7ae21983f2a4c740498c0794d35c9d7e5b');


// Gemini API Key (set your Gemini API key here)
define('GEMINI_API_KEY', '');
//Remember to encode the key

// Site configuration
$config = [
    // Site focus is on tour guides rather than tours
    'site_focus' => 'guides',
    
    // Featured sections on homepage
    'featured_sections' => [
        'top_guides' => true,
        'guide_categories' => true,
        'guide_testimonials' => true,
        'latest_reviews' => true
    ],
    
    // Contact information
    'contact' => [
        'email' => 'info@tourguideconnect.com',
        'phone' => '+1 (555) 123-4567',
        'address' => '123 Guide Street, Travel City'
    ],
    
    // Social media links
    'social_media' => [
        'facebook' => 'https://facebook.com/tourguideconnect',
        'instagram' => 'https://instagram.com/tourguideconnect',
        'twitter' => 'https://twitter.com/tourguideconnect'
    ]
];