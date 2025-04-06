<?php
/**
 * User Model
 * Handles all user-related database operations
 */
class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $bio;
    public $created_at;
    public $updated_at;
    
    private $db;
    
    /**
     * Constructor - initialize database connection
     */
    public function __construct() {
        $this->db = getDbConnection();
    }
    
    /**
     * Find user by ID
     * 
     * @param int $id User ID
     * @return array|false User data or false if not found
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Find user by email
     * 
     * @param string $email User email
     * @return array|false User data or false if not found
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Create a new user
     * 
     * @param array $data User data
     * @return int|false The new user ID or false on failure
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, location, bio, is_guide, phone) 
            VALUES (:name, :email, :password, :location, :bio, :is_guide, :phone)
        ");
        
        $result = $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'location' => $data['location'] ?? null,
            'bio' => $data['bio'] ?? null,
            'is_guide' => $data['is_guide'] ?? 0,
            'phone' => $data['phone'] ?? null
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update user information
     * 
     * @param int $id User ID
     * @param array $data User data to update
     * @return bool Success status
     */
    public function update($id, $data) {
        $sql = "UPDATE users SET ";
        $params = [];
        
        foreach ($data as $key => $value) {
            // Handle password separately
            if ($key === 'password') {
                $sql .= "$key = :$key, ";
                $params[$key] = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $sql .= "$key = :$key, ";
                $params[$key] = $value;
            }
        }
        
        $sql .= "updated_at = NOW() WHERE id = :id";
        $params['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Verify user password
     * 
     * @param int $id User ID
     * @param string $password Password to verify
     * @return bool True if password matches, false otherwise
     */
    public function verifyPassword($id, $password) {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Authenticate user
     * 
     * @param string $email User email
     * @param string $password User password
     * @return array|false User data if authenticated, false otherwise
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Check if email exists
     * 
     * @param string $email Email to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Check if email exists for user other than specified ID
     * 
     * @param string $email Email to check
     * @param int $userId User ID to exclude
     * @return bool True if email exists for another user, false otherwise
     */
    public function emailExistsExcept($email, $userId) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $stmt->execute(['email' => $email, 'id' => $userId]);
        return $stmt->fetch() !== false;
    }
    
    // Create new user
    public static function createUser($name, $email, $password) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception("Email already exists");
        }
        
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        $now = date('Y-m-d H:i:s');
        $result = $stmt->execute([$name, $email, $password, $now, $now]);
        
        if (!$result) {
            throw new Exception("Failed to create user");
        }
        
        // Get the newly created user ID
        $id = $conn->lastInsertId();
        
        // Return new user object
        return new User($id, $name, $email, $password, '', $now, $now);
    }
    
    // Update user profile
    public function updateProfile($name, $email, $bio) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Check if email is being changed and already exists
        if ($email !== $this->email) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $this->id]);
            
            if ($stmt->rowCount() > 0) {
                throw new Exception("Email already exists");
            }
        }
        
        // Update user data
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, bio = ?, updated_at = ? WHERE id = ?");
        $now = date('Y-m-d H:i:s');
        $result = $stmt->execute([$name, $email, $bio, $now, $this->id]);
        
        if (!$result) {
            throw new Exception("Failed to update user");
        }
        
        // Update object properties
        $this->name = $name;
        $this->email = $email;
        $this->bio = $bio;
        $this->updated_at = $now;
        
        return true;
    }
    
    // Change password
    public function changePassword($new_password) {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = ?, updated_at = ? WHERE id = ?");
        $now = date('Y-m-d H:i:s');
        $result = $stmt->execute([$new_password, $now, $this->id]);
        
        if (!$result) {
            throw new Exception("Failed to update password");
        }
        
        // Update object property
        $this->password = $new_password;
        $this->updated_at = $now;
        
        return true;
    }
    
    // Delete user account
    public function delete() {
        // Database connection
        $conn = require_once 'app/config/database.php';
        
        // Delete user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $result = $stmt->execute([$this->id]);
        
        if (!$result) {
            throw new Exception("Failed to delete user");
        }
        
        return true;
    }
    
    // Check if user is a tour guide
    public function isTourGuide() {
        return TourGuide::findByUserId($this->id) !== null;
    }
} 