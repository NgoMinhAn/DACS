<?php
// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Adding Reviews Table</h1>";

// Connect to database
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=local_guides', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>Connected to database successfully!</p>";
    
    // Check if reviews table exists
    $tableExists = $pdo->query("SHOW TABLES LIKE 'reviews'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "<p>Creating reviews table...</p>";
        
        // Create reviews table
        $pdo->exec("
        CREATE TABLE reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            booking_id INT,
            user_id INT NOT NULL,
            guide_id INT NOT NULL,
            rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (guide_id) REFERENCES guides(id) ON DELETE CASCADE
        )");
        
        echo "<p>✓ Created reviews table</p>";
        
        // Insert sample reviews
        $pdo->exec("
        INSERT INTO reviews (user_id, guide_id, rating, comment) VALUES 
        (2, 1, 5, 'John was an excellent guide! Very knowledgeable about Roman history.'),
        (1, 2, 4, 'Jane showed us the best local restaurants and cafes in Paris.'),
        (2, 2, 5, 'The food tour with Jane was the highlight of our trip!')
        ");
        
        echo "<p>✓ Added sample reviews</p>";
        
        echo "<p style='color:green'>Reviews table created successfully!</p>";
    } else {
        echo "<p>Reviews table already exists.</p>";
    }
    
    // Verify all required tables
    $requiredTables = ['users', 'guides', 'reviews'];
    $allTablesExist = true;
    
    echo "<h2>Checking required tables:</h2>";
    echo "<ul>";
    foreach ($requiredTables as $table) {
        $exists = $pdo->query("SHOW TABLES LIKE '$table'")->rowCount() > 0;
        echo "<li>" . $table . ": " . ($exists ? "✓ Exists" : "✗ Missing") . "</li>";
        if (!$exists) {
            $allTablesExist = false;
        }
    }
    echo "</ul>";
    
    if ($allTablesExist) {
        echo "<p style='color:green'><strong>All required tables exist! Your application should work now.</strong></p>";
    } else {
        echo "<p style='color:red'><strong>Some tables are still missing. Please fix them.</strong></p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?> 