<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/config.php';

function createDummyReviews($pdo) {
    // Fetch all guide profiles
    $stmt = $pdo->query("SELECT id FROM guide_profiles");
    $guides = $stmt->fetchAll();
    if (!$guides) {
        echo "No guide profiles found.\n";
        return;
    }

    // Dummy review data to be inserted for each guide
    $reviews = [
        [
            'user_id' => 4, // Assumes these user IDs exist in the database
            'rating' => 5,
            'review_text' => 'Excellent guide, very knowledgeable!',
            'status' => 'approved'
        ],
        [
            'user_id' => 5,
            'rating' => 4,
            'review_text' => 'Great tour, but room for improvement.',
            'status' => 'approved'
        ],
        // Additional dummy reviews
        [
            'user_id' => 6,
            'rating' => 5,
            'review_text' => 'Outstanding tour, a truly memorable experience!',
            'status' => 'approved'
        ],
        [
            'user_id' => 4,
            'rating' => 3,
            'review_text' => 'Good tour but a bit rushed.',
            'status' => 'approved'
        ],
        [
            'user_id' => 5,
            'rating' => 4,
            'review_text' => 'I enjoyed the tour, would like to try again.',
            'status' => 'approved'
        ]
    ];

    foreach ($guides as $guide) {
        foreach ($reviews as $review) {
            try {
                $stmt = $pdo->prepare("INSERT INTO guide_reviews (guide_id, user_id, rating, review_text, status) VALUES (:guide_id, :user_id, :rating, :review_text, :status)");
                $stmt->execute([
                    ':guide_id' => $guide['id'],
                    ':user_id' => $review['user_id'],
                    ':rating' => $review['rating'],
                    ':review_text' => $review['review_text'],
                    ':status' => $review['status']
                ]);
                echo "Inserted review for guide ID: {$guide['id']}\n";
            } catch (Exception $e) {
                echo "Failed to insert review for guide ID: {$guide['id']}. Error: " . $e->getMessage() . "\n";
            }
        }
    }
}

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    createDummyReviews($pdo);
    echo "Dummy reviews creation complete.\n";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
