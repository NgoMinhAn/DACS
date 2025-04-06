<?php
// Super simple database connection test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Basic Database Test</h1>";

try {
    echo "Connecting to MySQL...";
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    echo " SUCCESS!<br>";
    
    echo "Checking if 'local_guides' database exists...";
    $dbExists = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'local_guides'")->fetchColumn();
    
    if (!$dbExists) {
        echo " NOT FOUND. Creating database...";
        $pdo->exec("CREATE DATABASE local_guides");
        echo " CREATED!<br>";
    } else {
        echo " EXISTS!<br>";
    }
    
    echo "Connecting to 'local_guides' database...";
    $pdo = new PDO('mysql:host=localhost;dbname=local_guides', 'root', '');
    echo " SUCCESS!<br>";
    
    echo "<p>MySQL connection is working correctly!</p>";
} catch (PDOException $e) {
    echo " ERROR!<br>";
    echo "<p>Connection failed: " . $e->getMessage() . "</p>";
} 