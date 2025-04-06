<?php
/**
 * Database Configuration
 */

// Database credentials
define('DB_HOST', '127.0.0.1'); // Using IP instead of localhost
define('DB_NAME', 'local_guides');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('DB_PORT', '3306'); // Explicitly set port

/**
 * Get database connection
 * 
 * @return PDO Database connection
 */
function getDbConnection() {
    static $pdo;
    
    if (!$pdo) {
        try {
            // Try with explicit IP and port
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // Test connection
            $pdo->query("SELECT 1");
            
        } catch (PDOException $e) {
            // First connection attempt failed, try alternative methods
            try {
                // Try with socket connection (common in XAMPP/Laragon)
                $dsn = "mysql:unix_socket=D:/laragon/bin/mysql/mysql-8.0/data/mysql.sock;dbname=" . DB_NAME;
                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $socketEx) {
                // Socket connection failed, try without database name
                if (strpos($e->getMessage(), "Unknown database") !== false || 
                    strpos($socketEx->getMessage(), "Unknown database") !== false) {
                    try {
                        // Connect without database to create it
                        $rootDsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
                        $rootPdo = new PDO($rootDsn, DB_USER, DB_PASS, $options);
                        
                        // Create the database
                        $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`");
                        
                        // Try again with newly created database
                        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
                        return $pdo;
                    } catch (PDOException $createEx) {
                        error_log("Failed to create database: " . $createEx->getMessage());
                        die("Could not create database. MySQL error: " . $createEx->getMessage());
                    }
                } else {
                    // Log both the original and socket errors
                    error_log("Database Connection Error (IP): " . $e->getMessage());
                    error_log("Database Connection Error (Socket): " . $socketEx->getMessage());
                    
                    // Show detailed error in development mode
                    if (ini_get('display_errors') === '1') {
                        die("Database connection failed:<br>Original error: " . $e->getMessage() . 
                            "<br>Socket error: " . $socketEx->getMessage());
                    } else {
                        die("Could not connect to the database. Please try again later.");
                    }
                }
            }
        }
    }
    
    return $pdo;
} 