<?php
// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Fix</h1>";

// Connect to database
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=local_guides', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>Connected to database successfully!</p>";
    
    // Check if tables exist
    $tablesExist = $pdo->query("SHOW TABLES LIKE 'guides'")->rowCount() > 0;
    
    if (!$tablesExist) {
        echo "<p>Creating tables...</p>";
        
        // Recreate the guides table
        $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            location VARCHAR(100),
            bio TEXT,
            profile_image VARCHAR(255),
            is_guide BOOLEAN DEFAULT 0,
            is_admin BOOLEAN DEFAULT 0,
            email_verified_at TIMESTAMP NULL,
            remember_token VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        echo "<p>✓ Created users table</p>";
        
        // Create guides table
        $pdo->exec("
        CREATE TABLE IF NOT EXISTS guides (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            category_id INT NULL,
            speciality VARCHAR(100),
            hourly_rate DECIMAL(10, 2) NOT NULL,
            experience INT, -- years of experience
            rating DECIMAL(3, 2) DEFAULT 0,
            review_count INT DEFAULT 0,
            verified BOOLEAN DEFAULT 0,
            bio TEXT,
            location VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");
        
        echo "<p>✓ Created guides table</p>";
        
        // Insert sample data
        $pdo->exec("
        -- Create sample user accounts (password: password)
        INSERT INTO users (name, email, password, location, bio, is_guide, profile_image) VALUES 
        ('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rome, Italy', 'Passionate historian', 1, 'https://via.placeholder.com/150'),
        ('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Paris, France', 'Food enthusiast', 1, 'https://via.placeholder.com/150');
        
        -- Create guide profiles
        INSERT INTO guides (user_id, speciality, hourly_rate, experience, rating, review_count, location) VALUES 
        (1, 'Historical Sites', 45.00, 10, 4.5, 120, 'Rome, Italy'),
        (2, 'Local Cuisine', 50.00, 8, 5.0, 87, 'Paris, France');
        ");
        
        echo "<p>✓ Added sample data</p>";
        
        echo "<p style='color:green'>Database tables created successfully!</p>";
    } else {
        echo "<p>Tables already exist.</p>";
    }
    
    // Verify the tables
    $checkGuides = $pdo->query("SHOW TABLES LIKE 'guides'");
    if ($checkGuides->rowCount() > 0) {
        echo "<p style='color:green'>✓ Verified 'guides' table exists</p>";
    } else {
        echo "<p style='color:red'>✗ 'guides' table still does NOT exist!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?> 