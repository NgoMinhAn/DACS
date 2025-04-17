<?php
/**
 * User Model
 * Handles all database operations related to users
 */
class UserModel {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Login user
     * 
     * @param string $email The user's email
     * @param string $password The user's password
     * @return object|boolean The user object or false
     */
    public function login($email, $password) {
        // Find user by email
        $this->db->query('SELECT * FROM users WHERE email = :email AND status = "active"');
        $this->db->bind(':email', $email);
        
        $user = $this->db->single();
        
        // If user found, verify password
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        
        return false;
    }
    
    /**
     * Find user by email
     * 
     * @param string $email The user's email
     * @return object|boolean The user object or false
     */
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        return $row ? $row : false;
    }
    
    /**
     * Find user by ID
     * 
     * @param int $id The user ID
     * @return object|boolean The user object or false
     */
    public function findUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row ? $row : false;
    }
    
    /**
     * Find user by remember token
     * 
     * @param string $token The remember token
     * @return object|boolean The user object or false
     */
    public function findUserByRememberToken($token) {
        $this->db->query('SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()');
        $this->db->bind(':token', $token);
        
        $row = $this->db->single();
        
        return $row ? $row : false;
    }
    
    /**
     * Register new user
     * 
     * @param array $data The user data
     * @return boolean Whether the registration was successful
     */
    public function register($data) {
        // Hash password
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insert new user
        $this->db->query('
            INSERT INTO users (name, email, password, user_type, status, verification_token) 
            VALUES (:name, :email, :password, :user_type, :status, :token)
        ');
        
        // Generate verification token
        $token = bin2hex(random_bytes(32));
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $password);
        $this->db->bind(':user_type', $data['user_type'] ?? 'user');
        $this->db->bind(':status', $data['status'] ?? 'pending');
        $this->db->bind(':token', $token);
        
        // Execute query
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    /**
     * Register new guide
     * 
     * @param array $userData The user data
     * @param array $guideData The guide profile data
     * @return boolean Whether the registration was successful
     */
    public function registerGuide($userData, $guideData) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Register the user first
            $userData['user_type'] = 'guide';
            $userData['status'] = 'pending'; // Guides need approval
            
            $userId = $this->register($userData);
            
            if (!$userId) {
                throw new Exception('Failed to create user account');
            }
            
            // Now create the guide profile
            $this->db->query('
                INSERT INTO guide_profiles (
                    user_id, bio, location, experience_years, 
                    hourly_rate, daily_rate, available, verified
                ) VALUES (
                    :user_id, :bio, :location, :experience_years, 
                    :hourly_rate, :daily_rate, :available, :verified
                )
            ');
            
            // Bind values
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':bio', $guideData['bio']);
            $this->db->bind(':location', $guideData['location']);
            $this->db->bind(':experience_years', $guideData['experience_years']);
            $this->db->bind(':hourly_rate', $guideData['hourly_rate']);
            $this->db->bind(':daily_rate', $guideData['daily_rate']);
            $this->db->bind(':available', $guideData['available'] ?? 1);
            $this->db->bind(':verified', 0); // New guides start unverified
            
            // Execute query
            if (!$this->db->execute()) {
                throw new Exception('Failed to create guide profile');
            }
            
            $guideId = $this->db->lastInsertId();
            
            // Add specialties if provided
            if (!empty($guideData['specialties'])) {
                foreach ($guideData['specialties'] as $specialtyId) {
                    $this->db->query('
                        INSERT INTO guide_specialties (guide_id, specialty_id)
                        VALUES (:guide_id, :specialty_id)
                    ');
                    
                    $this->db->bind(':guide_id', $guideId);
                    $this->db->bind(':specialty_id', $specialtyId);
                    
                    if (!$this->db->execute()) {
                        throw new Exception('Failed to add guide specialty');
                    }
                }
            }
            
            // Add languages if provided
            if (!empty($guideData['languages'])) {
                foreach ($guideData['languages'] as $language) {
                    $this->db->query('
                        INSERT INTO guide_languages (guide_id, language_id, fluency_level)
                        VALUES (:guide_id, :language_id, :fluency_level)
                    ');
                    
                    $this->db->bind(':guide_id', $guideId);
                    $this->db->bind(':language_id', $language['id']);
                    $this->db->bind(':fluency_level', $language['fluency']);
                    
                    if (!$this->db->execute()) {
                        throw new Exception('Failed to add guide language');
                    }
                }
            }
            
            // Commit the transaction
            $this->db->commit();
            
            return $userId;
            
        } catch (Exception $e) {
            // Roll back the transaction on failure
            $this->db->rollBack();
            error_log('Guide registration error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user's last login time
     * 
     * @param int $userId The user ID
     * @return boolean Whether the update was successful
     */
    public function updateLastLogin($userId) {
        $this->db->query('UPDATE users SET last_login = NOW() WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
    
    /**
     * Set remember token for a user
     * 
     * @param int $userId The user ID
     * @param string $token The remember token
     * @param int $expires The expiration timestamp
     * @return boolean Whether the update was successful
     */
    public function setRememberToken($userId, $token, $expires) {
        $this->db->query('
            UPDATE users 
            SET reset_token = :token, reset_token_expires = FROM_UNIXTIME(:expires) 
            WHERE id = :id
        ');
        
        $this->db->bind(':id', $userId);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        
        return $this->db->execute();
    }
    
    /**
     * Clear remember token for a user
     * 
     * @param int $userId The user ID
     * @return boolean Whether the update was successful
     */
    public function clearRememberToken($userId) {
        $this->db->query('UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
    
    /**
     * Create password reset token
     * 
     * @param string $email The user's email
     * @return string|boolean The reset token or false
     */
    public function createPasswordResetToken($email) {
        // Find user by email
        $user = $this->findUserByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = time() + (60 * 60); // 1 hour
        
        // Update user with reset token
        $this->db->query('
            UPDATE users 
            SET reset_token = :token, reset_token_expires = FROM_UNIXTIME(:expires) 
            WHERE id = :id
        ');
        
        $this->db->bind(':id', $user->id);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        
        return $this->db->execute() ? $token : false;
    }
    
    /**
     * Verify email
     * 
     * @param string $token The verification token
     * @return boolean Whether the verification was successful
     */
    public function verifyEmail($token) {
        $this->db->query('
            UPDATE users 
            SET status = "active", verification_token = NULL 
            WHERE verification_token = :token AND status = "pending"
        ');
        
        $this->db->bind(':token', $token);
        
        return $this->db->execute() && $this->db->rowCount() > 0;
    }
} 