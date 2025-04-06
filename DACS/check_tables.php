<?php
// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Table Check</h1>";

// Connect to database
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=local_guides', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>Connected to database successfully!</p>";
    
    // Get list of tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Tables in database:</h2>";
    echo "<ul>";
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "<li>{$table}</li>";
        }
    } else {
        echo "<li>No tables found!</li>";
    }
    echo "</ul>";
    
    // Check specifically for guides table
    $checkGuides = $pdo->query("SHOW TABLES LIKE 'guides'");
    if ($checkGuides->rowCount() > 0) {
        echo "<p style='color:green'>✓ 'guides' table exists</p>";
        
        // Check structure
        $schema = $pdo->query("DESCRIBE guides");
        echo "<h3>Structure of guides table:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $schema->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>" . ($value ?? "NULL") . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red'>✗ 'guides' table does NOT exist!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?> 