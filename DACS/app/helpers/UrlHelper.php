<?php
/**
 * URL Helper Class
 * Helps generate correct URLs with the appropriate base path
 */
class UrlHelper {
    /**
     * The base path of the application
     * @var string
     */
    private static $basePath = '/DACS';
    
    /**
     * Generate a URL with the correct base path
     * @param string $path The path without leading slash
     * @return string The complete URL with base path
     */
    public static function url($path = '') {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Return the URL with base path
        return self::$basePath . '/' . $path;
    }
    
    /**
     * Generate an asset URL (for CSS, JS, images, etc.)
     * @param string $path The path to the asset
     * @return string The complete asset URL
     */
    public static function asset($path = '') {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Return the asset URL
        return self::$basePath . '/assets/' . $path;
    }
} 