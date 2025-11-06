<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/config.php';

function createDummyGuides($pdo) {
    $guides = [
        [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Experienced guide specializing in historical tours.',
            'location' => 'New York, USA',
            'experience_years' => 8,
            'hourly_rate' => 45.00,
            'daily_rate' => 350.00,
            'specialties' => ['Historical Tours', 'Architecture', 'City Tours'],
            'languages' => ['English', 'French']
        ],
        [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Food enthusiast and culinary expert offering the best food tours.',
            'location' => 'Barcelona, Spain',
            'experience_years' => 6,
            'hourly_rate' => 55.00,
            'daily_rate' => 400.00,
            'specialties' => ['Food & Cuisine', 'Cultural Immersion', 'Off the Beaten Path'],
            'languages' => ['English', 'Spanish']
        ],
        [
            'name' => 'Alice Johnson',
            'email' => 'alice.johnson@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Culinary expert with a passion for wine tasting.',
            'location' => 'Paris, France',
            'experience_years' => 5,
            'hourly_rate' => 60.00,
            'daily_rate' => 420.00,
            'specialties' => ['Food & Cuisine'],
            'languages' => ['English', 'Italian']
        ],
        [
            'name' => 'Bob Brown',
            'email' => 'bob.brown@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Adventure guide with a love for hiking.',
            'location' => 'Denver, USA',
            'experience_years' => 7,
            'hourly_rate' => 50.00,
            'daily_rate' => 380.00,
            'specialties' => ['Adventure', 'Hiking'],
            'languages' => ['English', 'German']
        ],
        [
            'name' => 'Charlie Davis',
            'email' => 'charlie.davis@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Passionate about photography and bird watching.',
            'location' => 'London, UK',
            'experience_years' => 4,
            'hourly_rate' => 40.00,
            'daily_rate' => 300.00,
            'specialties' => ['Photography', 'Bird Watching'],
            'languages' => ['English', 'Japanese']
        ],
        [
            'name' => 'Diana Evans',
            'email' => 'diana.evans@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Urban explorer with a flair for architecture tours.',
            'location' => 'Sydney, Australia',
            'experience_years' => 9,
            'hourly_rate' => 65.00,
            'daily_rate' => 450.00,
            'specialties' => ['Architecture', 'Urban Exploration'],
            'languages' => ['English', 'Portuguese']
        ],
        [
            'name' => 'Ethan Foster',
            'email' => 'ethan.foster@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Cultural tours expert with historical insights.',
            'location' => 'Rome, Italy',
            'experience_years' => 10,
            'hourly_rate' => 70.00,
            'daily_rate' => 500.00,
            'specialties' => ['Historical Tours', 'Cultural Tours'],
            'languages' => ['English', 'Russian']
        ],
        [
            'name' => 'Fiona Green',
            'email' => 'fiona.green@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Wildlife safari guide with local knowledge.',
            'location' => 'Nairobi, Kenya',
            'experience_years' => 6,
            'hourly_rate' => 55.00,
            'daily_rate' => 420.00,
            'specialties' => ['Wildlife', 'Safari'],
            'languages' => ['English', 'Swahili']
        ],
        [
            'name' => 'George Hill',
            'email' => 'george.hill@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Enthusiast of fishing and boating adventures.',
            'location' => 'Amsterdam, Netherlands',
            'experience_years' => 5,
            'hourly_rate' => 48.00,
            'daily_rate' => 360.00,
            'specialties' => ['Fishing', 'Boating'],
            'languages' => ['English', 'Dutch']
        ],
        [
            'name' => 'Hannah Irving',
            'email' => 'hannah.irving@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Wellness and yoga tour guide promoting relaxation.',
            'location' => 'Bali, Indonesia',
            'experience_years' => 3,
            'hourly_rate' => 42.00,
            'daily_rate' => 320.00,
            'specialties' => ['Yoga', 'Wellness'],
            'languages' => ['English', 'Hindi']
        ],
        [
            'name' => 'Ian Jackson',
            'email' => 'ian.jackson@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Music lover offering exclusive concert tours.',
            'location' => 'Los Angeles, USA',
            'experience_years' => 4,
            'hourly_rate' => 60.00,
            'daily_rate' => 410.00,
            'specialties' => ['Music', 'Concert Tours'],
            'languages' => ['English', 'Korean']
        ],
        [
            'name' => 'Julia King',
            'email' => 'julia.king@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Literature and poetry guide with a passion for art.',
            'location' => 'Edinburgh, UK',
            'experience_years' => 5,
            'hourly_rate' => 55.00,
            'daily_rate' => 390.00,
            'specialties' => ['Literature', 'Poetry'],
            'languages' => ['English', 'Mandarin']
        ],
        [
            'name' => 'Kevin Lewis',
            'email' => 'kevin.lewis@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Sports enthusiast with expertise in stadium tours.',
            'location' => 'Madrid, Spain',
            'experience_years' => 7,
            'hourly_rate' => 58.00,
            'daily_rate' => 420.00,
            'specialties' => ['Sports', 'Stadium Tours'],
            'languages' => ['English', 'Spanish']
        ],
        [
            'name' => 'Laura Martin',
            'email' => 'laura.martin@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Botanical tour guide with deep knowledge of local gardens.',
            'location' => 'Singapore',
            'experience_years' => 4,
            'hourly_rate' => 50.00,
            'daily_rate' => 380.00,
            'specialties' => ['Gardening', 'Botanical Tours'],
            'languages' => ['English', 'French']
        ],
        [
            'name' => 'Michael Nelson',
            'email' => 'michael.nelson@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Technology and innovation guide for modern tours.',
            'location' => 'San Francisco, USA',
            'experience_years' => 6,
            'hourly_rate' => 65.00,
            'daily_rate' => 450.00,
            'specialties' => ['Technology', 'Innovation'],
            'languages' => ['English', 'Chinese']
        ],
        [
            'name' => 'Nina Owens',
            'email' => 'nina.owens@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Fashion and shopping tours with a stylish approach.',
            'location' => 'Milan, Italy',
            'experience_years' => 5,
            'hourly_rate' => 60.00,
            'daily_rate' => 430.00,
            'specialties' => ['Fashion', 'Shopping'],
            'languages' => ['English', 'Italian']
        ],
        [
            'name' => 'Oscar Perez',
            'email' => 'oscar.perez@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Street markets and food tour expert with local insights.',
            'location' => 'Mexico City, Mexico',
            'experience_years' => 6,
            'hourly_rate' => 55.00,
            'daily_rate' => 420.00,
            'specialties' => ['Food & Cuisine', 'Street Markets'],
            'languages' => ['English', 'Spanish']
        ],
        [
            'name' => 'Paula Quinn',
            'email' => 'paula.quinn@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Artistic guide with a focus on painting and creative tours.',
            'location' => 'Florence, Italy',
            'experience_years' => 5,
            'hourly_rate' => 58.00,
            'daily_rate' => 410.00,
            'specialties' => ['Art', 'Painting'],
            'languages' => ['English', 'German']
        ],
        [
            'name' => 'Robert Scott',
            'email' => 'robert.scott@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Experienced guide for war museum and historical tours.',
            'location' => 'Berlin, Germany',
            'experience_years' => 8,
            'hourly_rate' => 60.00,
            'daily_rate' => 430.00,
            'specialties' => ['Historical Tours', 'War Museums'],
            'languages' => ['English', 'Russian']
        ],
        [
            'name' => 'Sophia Turner',
            'email' => 'sophia.turner@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'bio' => 'Dance and cultural performances guide with exceptional creativity.',
            'location' => 'Seoul, South Korea',
            'experience_years' => 7,
            'hourly_rate' => 62.00,
            'daily_rate' => 440.00,
            'specialties' => ['Dance', 'Cultural Performances'],
            'languages' => ['English', 'Japanese']
        ]
    ];

    foreach ($guides as $guide) {
        try {
            $pdo->beginTransaction();

            // Insert into users table
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type, status) VALUES (:name, :email, :password, 'guide', 'active')");
            $stmt->execute([
                ':name' => $guide['name'],
                ':email' => $guide['email'],
                ':password' => $guide['password']
            ]);

            $userId = $pdo->lastInsertId();

            // Insert into guide_profiles table with required fields
            $stmt = $pdo->prepare("INSERT INTO guide_profiles (user_id, bio, location, experience_years, hourly_rate, daily_rate) VALUES (:user_id, :bio, :location, :experience_years, :hourly_rate, :daily_rate)");
            $stmt->execute([
                ':user_id' => $userId,
                ':bio' => $guide['bio'],
                ':location' => $guide['location'],
                ':experience_years' => $guide['experience_years'],
                ':hourly_rate' => $guide['hourly_rate'],
                ':daily_rate' => $guide['daily_rate']
            ]);

            $guideId = $pdo->lastInsertId();

            // Insert guide specialties into guide_specialties table
            foreach ($guide['specialties'] as $specialty) {
                // Lookup the specialty id
                $stmt = $pdo->prepare("SELECT id FROM specialties WHERE name = :name");
                $stmt->execute([':name' => $specialty]);
                $specialtyRow = $stmt->fetch();
                if ($specialtyRow) {
                    $specialtyId = $specialtyRow['id'];
                } else {
                    // If specialty doesn't exist, insert it
                    $stmt = $pdo->prepare("INSERT INTO specialties (name) VALUES (:name)");
                    $stmt->execute([':name' => $specialty]);
                    $specialtyId = $pdo->lastInsertId();
                }
                // Insert into guide_specialties mapping
                $stmt = $pdo->prepare("INSERT INTO guide_specialties (guide_id, specialty_id) VALUES (:guide_id, :specialty_id)");
                $stmt->execute([
                    ':guide_id' => $guideId,
                    ':specialty_id' => $specialtyId
                ]);
            }

            // Insert guide languages into guide_languages table
            foreach ($guide['languages'] as $language) {
                // Lookup the language id
                $stmt = $pdo->prepare("SELECT id FROM languages WHERE name = :name");
                $stmt->execute([':name' => $language]);
                $languageRow = $stmt->fetch();
                if ($languageRow) {
                    $languageId = $languageRow['id'];
                } else {
                    // If language doesn't exist, insert it
                    $stmt = $pdo->prepare("INSERT INTO languages (name, code) VALUES (:name, :code)");
                    // For code, use first two letters of language name in lowercase as default
                    $code = strtolower(substr($language, 0, 2));
                    $stmt->execute([':name' => $language, ':code' => $code]);
                    $languageId = $pdo->lastInsertId();
                }
                // Insert into guide_languages mapping with default fluency 'fluent'
                $stmt = $pdo->prepare("INSERT INTO guide_languages (guide_id, language_id, fluency_level) VALUES (:guide_id, :language_id, 'fluent')");
                $stmt->execute([
                    ':guide_id' => $guideId,
                    ':language_id' => $languageId
                ]);
            }

            $pdo->commit();
            echo "Successfully added guide: {$guide['name']}\n";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed to add guide: {$guide['name']}. Error: " . $e->getMessage() . "\n";
        }
    }
}

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    createDummyGuides($pdo);
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}