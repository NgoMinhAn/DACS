-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS TourGuide;
USE TourGuide;

-- Temporarily disable foreign key checks to avoid dependency errors while dropping
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if they exist (for clean installation)
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS vnpay_transactions;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS guide_languages;
DROP TABLE IF EXISTS guide_specialties;
DROP TABLE IF EXISTS guide_reviews;
DROP TABLE IF EXISTS guide_profiles;
DROP TABLE IF EXISTS account_recovery;
DROP TABLE IF EXISTS user_preferences;
DROP TABLE IF EXISTS languages;
DROP TABLE IF EXISTS specialties;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS guide_applications;
DROP TABLE IF EXISTS contact_requests;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Users table (both regular users and guides)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    google_id VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('user', 'guide', 'admin') NOT NULL DEFAULT 'user',
    profile_image VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    status ENUM('active', 'inactive', 'pending', 'banned') NOT NULL DEFAULT 'pending',
    verification_token VARCHAR(100) NULL,
    reset_token VARCHAR(100) NULL,
    reset_token_expires TIMESTAMP NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    hobbies TEXT NULL,
    UNIQUE INDEX google_id_idx (google_id)
);

-- Guide applications table (for admin approval process)
CREATE TABLE guide_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    specialty VARCHAR(255) NOT NULL,
    languages TEXT,
    bio TEXT NOT NULL,
    experience TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    reviewed_at DATETIME,
    reviewed_by INT,
    location VARCHAR(100),
    phone VARCHAR(20),
    certifications TEXT,
    profile_image VARCHAR(255),
    hourly_rate DECIMAL(10,2),
    daily_rate DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Account recovery questions for enhanced security
CREATE TABLE account_recovery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    security_question VARCHAR(255) NOT NULL,
    security_answer VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User preferences for customization
CREATE TABLE user_preferences (
    user_id INT PRIMARY KEY,
    theme VARCHAR(20) DEFAULT 'light',
    notifications BOOLEAN DEFAULT TRUE,
    language_preference VARCHAR(10) DEFAULT 'en',
    currency VARCHAR(3) DEFAULT 'USD',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Guide profiles table
CREATE TABLE guide_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    bio TEXT NOT NULL,
    location VARCHAR(100) NOT NULL,
    experience_years INT NOT NULL DEFAULT 0,
    hourly_rate DECIMAL(10, 2) NOT NULL,
    daily_rate DECIMAL(10, 2) NOT NULL,
    available BOOLEAN NOT NULL DEFAULT TRUE,
    featured BOOLEAN NOT NULL DEFAULT FALSE,
    availability_calendar JSON NULL,
    avg_rating DECIMAL(3, 2) DEFAULT 0,
    total_reviews INT DEFAULT 0,
    verified BOOLEAN NOT NULL DEFAULT FALSE,
    certification_info TEXT NULL,
    payment_info TEXT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Specialties/Categories table
CREATE TABLE specialties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL,
    icon VARCHAR(50) NULL,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Guide specialties (many-to-many relationship)
CREATE TABLE guide_specialties (
    guide_id INT NOT NULL,
    specialty_id INT NOT NULL,
    PRIMARY KEY (guide_id, specialty_id),
    FOREIGN KEY (guide_id) REFERENCES guide_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (specialty_id) REFERENCES specialties(id) ON DELETE CASCADE
);

-- Languages table
CREATE TABLE languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    code VARCHAR(10) NOT NULL UNIQUE
);

-- Guide languages (many-to-many relationship)
CREATE TABLE guide_languages (
    guide_id INT NOT NULL,
    language_id INT NOT NULL,
    fluency_level ENUM('basic', 'conversational', 'fluent', 'native') NOT NULL DEFAULT 'fluent',
    PRIMARY KEY (guide_id, language_id),
    FOREIGN KEY (guide_id) REFERENCES guide_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
);

-- Guide reviews table
CREATE TABLE guide_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_text TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    helpful_votes INT DEFAULT 0,
    FOREIGN KEY (guide_id) REFERENCES guide_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    user_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    total_hours DECIMAL(5, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'refunded') NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(50) NULL,
    special_requests TEXT NULL,
    number_of_people INT NOT NULL DEFAULT 1,
    meeting_location TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guide_id) REFERENCES guide_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- VNPay Transactions table
CREATE TABLE vnpay_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NULL,
    vnp_TxnRef VARCHAR(100) NOT NULL COMMENT 'Order ID/Transaction Reference',
    vnp_TransactionNo VARCHAR(100) NULL COMMENT 'VNPay Transaction Number',
    vnp_Amount DECIMAL(15, 2) NOT NULL COMMENT 'Transaction Amount',
    vnp_OrderInfo TEXT NULL COMMENT 'Order Information',
    vnp_ResponseCode VARCHAR(10) NULL COMMENT 'VNPay Response Code',
    vnp_TransactionStatus VARCHAR(10) NULL COMMENT 'VNPay Transaction Status',
    vnp_BankCode VARCHAR(50) NULL COMMENT 'Bank Code',
    vnp_BankTranNo VARCHAR(100) NULL COMMENT 'Bank Transaction Number',
    vnp_CardType VARCHAR(50) NULL COMMENT 'Card Type',
    vnp_PayDate DATETIME NULL COMMENT 'Payment Date',
    payment_status VARCHAR(20) DEFAULT 'pending' COMMENT 'Payment Status: pending, success, failed',
    payment_method VARCHAR(50) DEFAULT 'VNPay' COMMENT 'Payment Method',
    ip_address VARCHAR(50) NULL COMMENT 'User IP Address',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_vnp_txn_ref (vnp_TxnRef),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='VNPay payment transactions table';

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    delivered_at DATETIME NULL,
    read_at DATETIME NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
	FOREIGN KEY (sender_id) REFERENCES users(id)
);

-- Contact requests from users to guides
CREATE TABLE contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guide_id) REFERENCES guide_profiles(id) ON DELETE CASCADE
);

ALTER TABLE bookings MODIFY status ENUM('pending','confirmed','completed','cancelled','accepted','declined') NOT NULL DEFAULT 'pending';
-- Removed stray SELECT with non-existent columns
-- SELECT id, name, email, account_type AS role, balance FROM users;
-- experience_years already exists in guide_profiles; skip duplicate add
-- ALTER TABLE guide_profiles ADD COLUMN experience_years INT DEFAULT 0;
-- Additional legacy column not needed; keep schema minimal
-- ALTER TABLE guide_languages ADD COLUMN fluent TINYINT(1) NOT NULL DEFAULT 0;
-- Insert sample data
-- Users (password is 'password' hashed with bcrypt)
INSERT INTO users (name, email, password, user_type, status) VALUES
('John Smith', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guide', 'active'),
('Maria Garcia', 'maria@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guide', 'active'),
('Nguyen Van Minh', 'minh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guide', 'active'),
('Sarah Johnson', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active'),
('Robert Lee', 'robert@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active'),
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- User preferences
INSERT INTO user_preferences (user_id, theme, notifications, language_preference, currency) VALUES
(1, 'light', TRUE, 'en', 'USD'),
(2, 'light', TRUE, 'es', 'EUR'),
(3, 'dark', TRUE, 'vi', 'VND'),
(4, 'light', TRUE, 'en', 'USD'),
(5, 'dark', FALSE, 'en', 'USD'),
(6, 'dark', TRUE, 'en', 'USD');

-- Guide profiles
INSERT INTO guide_profiles (user_id, bio, location, experience_years, hourly_rate, daily_rate, featured, avg_rating, total_reviews, verified) VALUES
(1, 'Experienced guide specializing in historical tours with deep knowledge of local architecture and customs.', 'New York, USA', 8, 45.00, 350.00, TRUE, 4.8, 42, TRUE),
(2, 'Food enthusiast and culinary expert offering the best food tours in the region. Discover hidden gems and local delicacies.', 'Barcelona, Spain', 6, 55.00, 400.00, TRUE, 5.0, 38, TRUE),
(3, 'Adventure guide with expertise in outdoor activities. Perfect for those seeking thrilling experiences in nature.', 'Hanoi, Vietnam', 5, 50.00, 380.00, FALSE, 4.6, 29, TRUE);

-- Specialties
INSERT INTO specialties (name, description, icon) VALUES
('Historical Tours', 'Explore the rich history and heritage of a location', 'fa-landmark'),
('Food & Cuisine', 'Discover local flavors and culinary traditions', 'fa-utensils'),
('Adventure', 'Thrilling experiences for the adventurous traveler', 'fa-mountain'),
('Nature & Wildlife', 'Connect with nature and observe local wildlife', 'fa-leaf'),
('Architecture', 'Learn about local building styles and famous structures', 'fa-building'),
('Cultural Immersion', 'Experience local customs, traditions and way of life', 'fa-mask'),
('Off the Beaten Path', 'Explore hidden gems away from tourist crowds', 'fa-route'),
('City Tours', 'Comprehensive tours of urban environments', 'fa-city');

-- Guide specialties
INSERT INTO guide_specialties (guide_id, specialty_id) VALUES
(1, 1), -- John - Historical Tours
(1, 5), -- John - Architecture
(1, 8), -- John - City Tours
(2, 2), -- Maria - Food & Cuisine
(2, 6), -- Maria - Cultural Immersion
(2, 7), -- Maria - Off the Beaten Path
(3, 3), -- Minh - Adventure
(3, 4), -- Minh - Nature & Wildlife
(3, 7); -- Minh - Off the Beaten Path

-- Languages
INSERT INTO languages (name, code) VALUES
('English', 'en'),
('Spanish', 'es'),
('French', 'fr'),
('German', 'de'),
('Italian', 'it'),
('Vietnamese', 'vi'),
('Japanese', 'ja'),
('Korean', 'ko'),
('Mandarin', 'zh'),
('Russian', 'ru');

-- Guide languages
INSERT INTO guide_languages (guide_id, language_id, fluency_level) VALUES
(1, 1, 'native'), -- John - English (native)
(1, 3, 'fluent'), -- John - French (fluent)
(2, 1, 'fluent'), -- Maria - English (fluent)
(2, 2, 'native'), -- Maria - Spanish (native)
(3, 1, 'fluent'), -- Minh - English (fluent)
(3, 6, 'native'); -- Minh - Vietnamese (native)

-- Reviews
INSERT INTO guide_reviews (guide_id, user_id, rating, review_text, status) VALUES
(1, 4, 5, 'John was an excellent guide! His knowledge of local history made the tour incredibly informative and engaging.', 'approved'),
(1, 5, 4, 'Great historical tour, learned a lot about the architecture. Would recommend.', 'approved'),
(2, 4, 5, 'The food tour with Maria was amazing! We discovered so many hidden gems we would never have found on our own.', 'approved'),
(2, 5, 5, 'Maria\'s knowledge of local cuisine is impressive. Best food tour I\'ve ever taken.', 'approved'),
(3, 4, 4, 'Minh took us on an exciting adventure through the countryside. Very professional guide.', 'approved');

-- Bookings
INSERT INTO bookings (guide_id, user_id, booking_date, start_time, end_time, total_hours, total_price, status, payment_status, number_of_people, meeting_location) VALUES
(1, 4, '2023-07-15', '09:00:00', '12:00:00', 3, 135.00, 'completed', 'paid', 2, 'Central Park Entrance'),
(1, 5, '2023-07-20', '14:00:00', '17:00:00', 3, 135.00, 'completed', 'paid', 1, 'Museum Steps'),
(2, 4, '2023-08-05', '18:00:00', '21:00:00', 3, 165.00, 'completed', 'paid', 4, 'Main Square'),
(2, 5, '2023-09-10', '11:00:00', '15:00:00', 4, 220.00, 'confirmed', 'paid', 2, 'Market Entrance'),
(3, 4, '2023-08-15', '08:00:00', '16:00:00', 8, 400.00, 'completed', 'paid', 3, 'Hotel Lobby'),
(1, 5, '2023-10-25', '10:00:00', '13:00:00', 3, 135.00, 'pending', 'pending', 2, 'City Hall');

-- Create a view for guide listings with their top specialties
CREATE OR REPLACE VIEW guide_listings AS
SELECT 
    u.id AS user_id,
    g.id AS guide_id,
    u.name,
    u.email,
    u.profile_image,
    u.phone,
    g.bio,
    g.location,
    g.experience_years,
    g.hourly_rate,
    g.daily_rate,
    g.available,
    g.featured,
    g.avg_rating,
    g.total_reviews,
    g.verified,
    GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') AS specialties,
    GROUP_CONCAT(DISTINCT l.name SEPARATOR ', ') AS languages
FROM 
    users u
JOIN 
    guide_profiles g ON u.id = g.user_id
LEFT JOIN 
    guide_specialties gs ON g.id = gs.guide_id
LEFT JOIN 
    specialties s ON gs.specialty_id = s.id
LEFT JOIN 
    guide_languages gl ON g.id = gl.guide_id
LEFT JOIN 
    languages l ON gl.language_id = l.id
WHERE 
    u.status = 'active' AND u.user_type = 'guide'
GROUP BY 
    g.id
ORDER BY 
    g.featured DESC, g.avg_rating DESC;

-- Create or replace a stored procedure to update average ratings
DROP PROCEDURE IF EXISTS update_guide_rating;
DELIMITER //
CREATE PROCEDURE update_guide_rating(IN guide_id_param INT)
BEGIN
    DECLARE avg_rating_val DECIMAL(3,2);
    DECLARE total_reviews_val INT;
    
    -- Calculate the new average rating and total reviews
    SELECT 
        AVG(rating) as avg_rating,
        COUNT(*) as total_reviews
    INTO
        avg_rating_val,
        total_reviews_val
    FROM 
        guide_reviews
    WHERE 
        guide_id = guide_id_param AND
        status = 'approved';
    
    -- Update the guide profile
    UPDATE guide_profiles
    SET 
        avg_rating = COALESCE(avg_rating_val, 0),
        total_reviews = total_reviews_val
    WHERE 
        id = guide_id_param;
END //
DELIMITER ;

-- Create a procedure for updating user profiles
DROP PROCEDURE IF EXISTS update_user_profile;
DELIMITER //
CREATE PROCEDURE update_user_profile(
    IN user_id_param INT, 
    IN name_param VARCHAR(100),
    IN email_param VARCHAR(100),
    IN phone_param VARCHAR(20),
    IN address_param TEXT,
    IN profile_image_param VARCHAR(255)
)
BEGIN
    UPDATE users 
    SET 
        name = CASE WHEN name_param IS NOT NULL THEN name_param ELSE name END,
        email = CASE WHEN email_param IS NOT NULL THEN email_param ELSE email END,
        phone = CASE WHEN phone_param IS NOT NULL THEN phone_param ELSE phone END,
        address = CASE WHEN address_param IS NOT NULL THEN address_param ELSE address END,
        profile_image = CASE WHEN profile_image_param IS NOT NULL THEN profile_image_param ELSE profile_image END,
        updated_at = CURRENT_TIMESTAMP
    WHERE 
        id = user_id_param;
END //
DELIMITER ;

-- Create a procedure for updating guide profiles
DROP PROCEDURE IF EXISTS update_guide_profile;
DELIMITER //
CREATE PROCEDURE update_guide_profile(
    IN guide_id_param INT,
    IN bio_param TEXT,
    IN location_param VARCHAR(100),
    IN experience_years_param INT,
    IN hourly_rate_param DECIMAL(10, 2),
    IN daily_rate_param DECIMAL(10, 2),
    IN available_param BOOLEAN
)
BEGIN
    UPDATE guide_profiles 
    SET 
        bio = CASE WHEN bio_param IS NOT NULL THEN bio_param ELSE bio END,
        location = CASE WHEN location_param IS NOT NULL THEN location_param ELSE location END,
        experience_years = CASE WHEN experience_years_param IS NOT NULL THEN experience_years_param ELSE experience_years END,
        hourly_rate = CASE WHEN hourly_rate_param IS NOT NULL THEN hourly_rate_param ELSE hourly_rate END,
        daily_rate = CASE WHEN daily_rate_param IS NOT NULL THEN daily_rate_param ELSE daily_rate END,
        available = CASE WHEN available_param IS NOT NULL THEN available_param ELSE available END,
        updated_at = CURRENT_TIMESTAMP
    WHERE 
        id = guide_id_param;
END //
DELIMITER ;

-- Create a procedure for updating user preferences
DROP PROCEDURE IF EXISTS update_user_preferences;
DELIMITER //
CREATE PROCEDURE update_user_preferences(
    IN user_id_param INT,
    IN theme_param VARCHAR(20),
    IN notifications_param BOOLEAN,
    IN language_preference_param VARCHAR(10),
    IN currency_param VARCHAR(3)
)
BEGIN
    -- If preferences don't exist yet, insert them
    INSERT INTO user_preferences (user_id, theme, notifications, language_preference, currency)
    VALUES (user_id_param, 
            COALESCE(theme_param, 'light'), 
            COALESCE(notifications_param, TRUE), 
            COALESCE(language_preference_param, 'en'), 
            COALESCE(currency_param, 'USD'))
    ON DUPLICATE KEY UPDATE
        theme = CASE WHEN theme_param IS NOT NULL THEN theme_param ELSE theme END,
        notifications = CASE WHEN notifications_param IS NOT NULL THEN notifications_param ELSE notifications END,
        language_preference = CASE WHEN language_preference_param IS NOT NULL THEN language_preference_param ELSE language_preference END,
        currency = CASE WHEN currency_param IS NOT NULL THEN currency_param ELSE currency END;
END //
DELIMITER ;

-- Create a procedure for changing password
DROP PROCEDURE IF EXISTS change_user_password;
DELIMITER //
CREATE PROCEDURE change_user_password(
    IN user_id_param INT,
    IN new_password_param VARCHAR(255)
)
BEGIN
    UPDATE users
    SET 
        password = new_password_param,
        updated_at = CURRENT_TIMESTAMP,
        reset_token = NULL,
        reset_token_expires = NULL
    WHERE 
        id = user_id_param;
END //
DELIMITER ;

-- Create triggers to automatically update guide ratings
DROP TRIGGER IF EXISTS after_review_insert;
DROP TRIGGER IF EXISTS after_review_update;
DROP TRIGGER IF EXISTS after_review_delete;
DELIMITER //
CREATE TRIGGER after_review_insert
AFTER INSERT ON guide_reviews
FOR EACH ROW
BEGIN
    CALL update_guide_rating(NEW.guide_id);
END //

CREATE TRIGGER after_review_update
AFTER UPDATE ON guide_reviews
FOR EACH ROW
BEGIN
    CALL update_guide_rating(NEW.guide_id);
END //

CREATE TRIGGER after_review_delete
AFTER DELETE ON guide_reviews
FOR EACH ROW
BEGIN
    CALL update_guide_rating(OLD.guide_id);
END //
DELIMITER ;

-- Create indexes for improved performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_users_type ON users(user_type);
CREATE INDEX idx_guides_location ON guide_profiles(location);
CREATE INDEX idx_guides_hourly_rate ON guide_profiles(hourly_rate);
CREATE INDEX idx_guides_rating ON guide_profiles(avg_rating);
CREATE INDEX idx_reviews_rating ON guide_reviews(rating);
CREATE INDEX idx_bookings_date ON bookings(booking_date);
CREATE INDEX idx_bookings_status ON bookings(status);

-- Grant privileges (adjust as needed for your production environment)
GRANT ALL PRIVILEGES ON TourGuide.* TO 'root'@'localhost';
FLUSH PRIVILEGES;