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
// Use port 8081 for local Laragon setup
define('URL_ROOT', 'http://localhost:80');
//Just change your laragon port to 8081

// Site Name
define('SITE_NAME', 'Tour Guide Connect');

// App Version
define('APP_VERSION', '1.0.0');


// Gemini API Key (set your Gemini API key here)
define('GEMINI_API_KEY', 'AIzaSyCxIxv7AHMwBiyE1wnt_orOgJyiSFfWaFo');
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