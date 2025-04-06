-- Local Guides Database Setup
-- SQL setup script for creating all necessary tables and relationships
CREATE DATABASE TourGuide
-- Drop existing tables if they exist (for clean installation)
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS guide_availability;
DROP TABLE IF EXISTS guide_languages;
DROP TABLE IF EXISTS guide_specialties;
DROP TABLE IF EXISTS guide_categories;
DROP TABLE IF EXISTS guides;
DROP TABLE IF EXISTS users;

-- Create Users table
CREATE TABLE users (
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
);

-- Create Guide Categories table
CREATE TABLE guide_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Guides table (extends Users)
CREATE TABLE guides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT,
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES guide_categories(id) ON DELETE SET NULL
);

-- Create Guide Specialties table (many-to-many relationship)
CREATE TABLE guide_specialties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guide_id) REFERENCES guides(id) ON DELETE CASCADE
);

-- Create Guide Languages table
CREATE TABLE guide_languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    language VARCHAR(50) NOT NULL,
    proficiency VARCHAR(20), -- basic, conversational, fluent, native
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guide_id) REFERENCES guides(id) ON DELETE CASCADE
);

-- Create Guide Availability table
CREATE TABLE guide_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    day VARCHAR(10) NOT NULL, -- Monday, Tuesday, etc.
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guide_id) REFERENCES guides(id) ON DELETE CASCADE
);

-- Create Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- tourist
    guide_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    duration INT NOT NULL, -- in hours
    tour_type VARCHAR(100),
    num_people INT NOT NULL DEFAULT 1,
    meeting_point VARCHAR(255),
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (guide_id) REFERENCES guides(id) ON DELETE CASCADE
);

-- Create Reviews table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    user_id INT NOT NULL, -- tourist
    guide_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (guide_id) REFERENCES guides(id) ON DELETE CASCADE
);

-- Create Messages table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert some initial categories
INSERT INTO guide_categories (name, description) VALUES 
('Historical', 'Guides specializing in historical sites and architecture'),
('Cultural', 'Guides focusing on local culture, traditions, and lifestyle'),
('Culinary', 'Food experts showcasing local cuisine and markets'),
('Adventure', 'Specialists in outdoor activities and natural attractions'),
('Art & Museums', 'Experts in local art scenes, galleries, and museums');

-- Create an admin user (password: admin123)
INSERT INTO users (name, email, password, is_admin) VALUES 
('Admin', 'admin@localguides.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Create sample user accounts (password: password)
INSERT INTO users (name, email, password, location, bio, is_guide, profile_image) VALUES 
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rome, Italy', 'Passionate historian with deep knowledge of Roman architecture', 1, 'https://via.placeholder.com/150'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Paris, France', 'Food enthusiast with expertise in French cuisine and local markets', 1, 'https://via.placeholder.com/150'),
('Michael Johnson', 'michael@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Barcelona, Spain', 'Nature lover specializing in outdoor adventures and hiking trails', 1, 'https://via.placeholder.com/150'),
('Tourist User', 'tourist@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'London, UK', 'Avid traveler looking for authentic experiences', 0, 'https://via.placeholder.com/150');

-- Create guide profiles for the sample users
INSERT INTO guides (user_id, category_id, speciality, hourly_rate, experience, rating, review_count, location) VALUES 
(1, 1, 'Historical Sites & Architecture', 45.00, 10, 4.5, 120, 'Rome, Italy'),
(2, 3, 'Local Cuisine & Markets', 50.00, 8, 5.0, 87, 'Paris, France'),
(3, 4, 'Outdoor Adventures & Nature', 40.00, 5, 4.0, 65, 'Barcelona, Spain');

-- Add languages for guides
INSERT INTO guide_languages (guide_id, language, proficiency) VALUES 
(1, 'English', 'fluent'),
(1, 'Italian', 'native'),
(1, 'Spanish', 'conversational'),
(2, 'English', 'fluent'),
(2, 'French', 'native'),
(3, 'English', 'fluent'),
(3, 'Spanish', 'native'),
(3, 'Catalan', 'native');
--
-- Add specialties for guides
INSERT INTO guide_specialties (guide_id, specialty) VALUES 
(1, 'Ancient Rome'),
(1, 'Roman Architecture'),
(1, 'Vatican Tours'),
(2, 'Wine Tasting'),
(2, 'Cheese Tours'),
(2, 'Local Markets'),
(3, 'Hiking'),
(3, 'Mountain Biking'),
(3, 'Nature Photography');

-- Add availability for guides
INSERT INTO guide_availability (guide_id, day, start_time, end_time) VALUES 
(1, 'Monday', '09:00:00', '17:00:00'),
(1, 'Wednesday', '09:00:00', '17:00:00'),
(1, 'Friday', '09:00:00', '17:00:00'),
(2, 'Tuesday', '10:00:00', '16:00:00'),
(2, 'Thursday', '10:00:00', '16:00:00'),
(2, 'Saturday', '10:00:00', '14:00:00'),
(3, 'Monday', '08:00:00', '15:00:00'),
(3, 'Thursday', '08:00:00', '15:00:00'),
(3, 'Sunday', '08:00:00', '12:00:00');

-- Add sample bookings
INSERT INTO bookings (user_id, guide_id, date, start_time, end_time, duration, tour_type, num_people, meeting_point, status, total_amount, payment_status) VALUES 
(4, 1, DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY), '10:00:00', '13:00:00', 3, 'Historical Tour', 2, 'Colosseum Main Entrance', 'confirmed', 135.00, 'paid'),
(4, 2, DATE_ADD(CURRENT_DATE, INTERVAL 14 DAY), '11:00:00', '14:00:00', 3, 'Food Tour', 2, 'Notre Dame Cathedral', 'pending', 150.00, 'pending'),
(4, 3, DATE_ADD(CURRENT_DATE, INTERVAL -14 DAY), '09:00:00', '13:00:00', 4, 'Hiking Tour', 1, 'Park Güell Entrance', 'completed', 160.00, 'paid');

-- Add sample reviews
INSERT INTO reviews (booking_id, user_id, guide_id, rating, comment) VALUES 
(3, 4, 3, 4, 'Michael was a fantastic guide! He showed us beautiful hiking trails with breathtaking views. Knowledgeable about local flora and fauna. Would recommend for nature lovers.');

-- Add sample messages
INSERT INTO messages (sender_id, recipient_id, subject, message) VALUES 
(4, 1, 'Question about Colosseum tour', 'Hi John, I was wondering if your historical tour includes access to the restricted areas of the Colosseum? Thanks!'),
(1, 4, 'RE: Question about Colosseum tour', 'Hello! Yes, my tour includes access to the underground chambers and the third tier of the Colosseum. Looking forward to showing you around!'); 