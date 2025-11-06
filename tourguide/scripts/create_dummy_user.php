<?php
/**
 * Script to create a dummy user with sample hobbies and search history
 * Run from the project root with: php scripts/create_dummy_user.php
 *
 * This script uses the app's Database class and config to insert a user,
 * user_preferences, and user_searches rows (if the table exists).
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/php-config.php';
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';

$db = new Database();

// Safety: prompt for confirmation when run interactively
echo "This will insert multiple dummy users into the database defined in app/config/config.php (DB_NAME). Continue? (y/N): ";
$handle = fopen('php://stdin', 'r');
$line = strtolower(trim(fgets($handle)));
if ($line !== 'y' && $line !== 'yes') {
    echo "Aborted.\n";
    exit;
}

// Ensure user_searches table exists (non-destructive)
$db->query("CREATE TABLE IF NOT EXISTS user_searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    query VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$db->execute();

// Define multiple dummy users
$dummyUsers = [
    [
        'name' => 'Demo User',
        'email' => 'demo.user+auto@example.com',
        'password' => 'Password123!',
        'hobbies' => ['photography', 'food', 'history'],
        'searches' => ['food tours', 'historical tours', 'city tours near me']
    ],
    [
        'name' => 'Alice Traveler',
        'email' => 'alice.traveler@example.com',
        'password' => 'AlicePass1!',
        'hobbies' => ['hiking', 'birdwatching'],
        'searches' => ['adventure experiences', 'nature & wildlife']
    ],
    [
        'name' => 'Bob Explorer',
        'email' => 'bob.explorer@example.com',
        'password' => 'BobPass1!',
        'hobbies' => ['architecture', 'food'],
        'searches' => ['architecture guides', 'city tours']
    ],
    [
        'name' => 'Carol Foodie',
        'email' => 'carol.foodie@example.com',
        'password' => 'CarolPass1!',
        'hobbies' => ['cooking', 'food', 'markets'],
        'searches' => ['food tours', 'local markets']
    ]
];

foreach ($dummyUsers as $u) {
    try {
        $db->beginTransaction();

        // Check existing
        $db->query('SELECT id FROM users WHERE email = :email');
        $db->bind(':email', $u['email']);
        $exists = $db->single();
        if ($exists) {
            echo "Skipping existing user {$u['email']} (id={$exists->id})\n";
            $db->rollBack();
            continue;
        }

        // Insert user
        $db->query('INSERT INTO users (name, email, password, user_type, status, hobbies) VALUES (:name, :email, :password, :user_type, :status, :hobbies)');
        $db->bind(':name', $u['name']);
        $db->bind(':email', $u['email']);
        $db->bind(':password', password_hash($u['password'], PASSWORD_DEFAULT));
        $db->bind(':user_type', 'user');
        $db->bind(':status', 'active');
        $db->bind(':hobbies', json_encode($u['hobbies']));
        $db->execute();

        $userId = $db->lastInsertId();
        echo "Inserted user id={$userId} ({$u['email']})\n";

        // Insert user preferences (ignore errors)
        $db->query('INSERT INTO user_preferences (user_id, theme, notifications, language_preference, currency) VALUES (:user_id, :theme, :notifications, :language_preference, :currency)');
        $db->bind(':user_id', $userId);
        $db->bind(':theme', 'light');
        $db->bind(':notifications', 1);
        $db->bind(':language_preference', 'en');
        $db->bind(':currency', 'USD');
        $db->execute();

        // Insert searches
        $db->query('INSERT INTO user_searches (user_id, query) VALUES (:user_id, :query)');
        foreach ($u['searches'] as $q) {
            $db->bind(':user_id', $userId);
            $db->bind(':query', $q);
            $db->execute();
        }

        $db->commit();
        echo "Completed seeding for {$u['email']}\n";
    } catch (Exception $e) {
        try { $db->rollBack(); } catch (Exception $rb) {}
        echo "Error creating user {$u['email']}: " . $e->getMessage() . "\n";
    }
}

echo "All done.\n";

?>