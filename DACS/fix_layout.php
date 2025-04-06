<?php
// Fix layout script
echo "<h1>Fixing Layout Files</h1>";

// Define paths
define('APP_PATH', __DIR__ . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('SHARES_PATH', VIEWS_PATH . '/shares');

// Fix header.php
$header_file = SHARES_PATH . '/header.php';
if (file_exists($header_file)) {
    echo "<p>Fixing header.php...</p>";
    
    // Get current content
    $content = file_get_contents($header_file);
    
    // Remove closing body and html tags
    $fixed_content = preg_replace('/<\/body>\s*<\/html>\s*$/i', '', $content);
    
    // Add container div if not present
    if (strpos($fixed_content, '<div class="container mt-4">') === false) {
        $fixed_content .= "\n    <div class=\"container mt-4\">\n    <!-- Content starts here -->\n";
    }
    
    // Write back to file
    file_put_contents($header_file, $fixed_content);
    echo "<p style='color:green'>✓ Header file fixed!</p>";
} else {
    echo "<p style='color:red'>✗ Header file not found!</p>";
}

// Check footer.php
$footer_file = SHARES_PATH . '/footer.php';
if (file_exists($footer_file)) {
    echo "<p>Footer.php is OK</p>";
    
    // Display the first few lines of footer
    $footer_content = file_get_contents($footer_file);
    $first_line = strtok($footer_content, "\n");
    echo "<p>First line of footer: <code>" . htmlspecialchars($first_line) . "</code></p>";
} else {
    echo "<p style='color:red'>✗ Footer file not found!</p>";
}

echo "<p>Layout files have been fixed. Please reload your main page now.</p>";
?> 