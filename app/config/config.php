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
define('URL_ROOT', 'http://localhost:80/DACS');

// Site Name
define('SITE_NAME', 'Tour Guide Connect');

// App Version
define('APP_VERSION', '1.0.0');

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