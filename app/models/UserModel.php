<?php
/**
 * User Model
 * Handles all database operations related to users
 */
class UserModel
{
    private $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Login user
     * 
     * @param string $email The user's email
     * @param string $password The user's password
     * @return object|boolean The user object or false
     */
    public function login($email, $password)
    {
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
    public function findUserByEmail($email)
    {
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
    public function findUserById($id)
    {
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
    public function findUserByRememberToken($token)
    {
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
    public function register($data)
    {
        // Hash password
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insert new user
        $this->db->query('
            INSERT INTO users (name, email, password, user_type, status, verification_token, google_id) 
            VALUES (:name, :email, :password, :user_type, :status, :token, :google_id)
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
        $this->db->bind(':google_id', $data['google_id'] ?? null);

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
    public function registerGuide($userData, $guideData)
    {
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
    public function updateLastLogin($userId)
    {
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
    public function setRememberToken($userId, $token, $expires)
    {
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
    public function clearRememberToken($userId)
    {
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
    public function createPasswordResetToken($email)
    {
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
    public function verifyEmail($token)
    {
        $this->db->query('
            UPDATE users 
            SET status = "active", verification_token = NULL 
            WHERE verification_token = :token AND status = "pending"
        ');

        $this->db->bind(':token', $token);

        return $this->db->execute() && $this->db->rowCount() > 0;
    }

    /**
     * Get total user count
     * 
     * @return int The number of users
     */
    public function getUserCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE user_type = "user"');
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    /**
     * Get total guide count
     * 
     * @return int The number of guides
     */
    public function getGuideCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE user_type = "guide"');
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    /**
     * Get all users (excluding guides and admins)
     * 
     * @return array The users
     */
    public function getAllUsers()
    {
        $this->db->query('SELECT id, name, email, status, created_at, last_login FROM users WHERE user_type = "user" ORDER BY name');
        return $this->db->resultSet();
    }

    /**
     * Get all guides
     * 
     * @return array The guides
     */
    public function getAllGuides()
    {
        $this->db->query('
            SELECT u.id, u.name, u.email, u.status, u.created_at, u.last_login, 
                   g.verified, g.avg_rating, g.total_reviews
            FROM users u
            JOIN guide_profiles g ON u.id = g.user_id
            WHERE u.user_type = "guide"
            ORDER BY g.verified DESC, u.name
        ');
        return $this->db->resultSet();
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Find user by email except current user
     */
    public function findUserByEmailExcept($email, $userId)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email AND id != :id');
        $this->db->bind(':email', $email);
        $this->db->bind(':id', $userId);
        return $this->db->single();
    }

    /**
     * Update user profile
     */
    public function updateProfile($data)
    {
        try {
            $this->db->beginTransaction();

            // Start with basic fields that exist in users table
            $sql = 'UPDATE users SET 
                    name = :name, 
                    email = :email, 
                    phone = :phone, 
                    address = :address';

            // Add avatar field if it exists
            if (isset($data['avatar'])) {
                $sql .= ', profile_image = :avatar';
            }

            $sql .= ' WHERE id = :id';

            $this->db->query($sql);

            // Bind basic parameters
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':id', $data['user_id']);

            // Bind avatar parameter if it exists
            if (isset($data['avatar'])) {
                $this->db->bind(':avatar', $data['avatar']);
            }

            // Execute update for users table
            if (!$this->db->execute()) {
                throw new Exception('Failed to update user profile');
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error updating profile: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify user password
     */
    public function verifyPassword($userId, $password)
    {
        $this->db->query('SELECT password FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);

        $row = $this->db->single();
        if ($row) {
            return password_verify($password, $row->password);
        }
        return false;
    }

    /**
     * Update user password
     */
    public function updatePassword($userId, $newPassword)
    {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    /**
     * Delete user account
     */
    public function deleteAccount($userId)
    {
        // First get user info to delete profile image if exists
        $this->db->query('SELECT profile_image FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        $user = $this->db->single();

        // Delete profile image file if exists and is not default
        if ($user && !empty($user->profile_image) && $user->profile_image !== 'default.jpg') {
            $imagePath = 'public/uploads/avatars/' . $user->profile_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Start transaction
        $this->db->beginTransaction();

        try {
            // If user is a guide, delete related records first
            $this->db->query('SELECT user_type FROM users WHERE id = :id');
            $this->db->bind(':id', $userId);
            $userType = $this->db->single();

            if ($userType && $userType->user_type === 'guide') {
                // Get guide_id first
                $this->db->query('SELECT id FROM guide_profiles WHERE user_id = :user_id');
                $this->db->bind(':user_id', $userId);
                $guide = $this->db->single();

                if ($guide) {
                    // Delete from guide_languages
                    $this->db->query('DELETE FROM guide_languages WHERE guide_id = :guide_id');
                    $this->db->bind(':guide_id', $guide->id);
                    $this->db->execute();

                    // Delete from guide_specialties
                    $this->db->query('DELETE FROM guide_specialties WHERE guide_id = :guide_id');
                    $this->db->bind(':guide_id', $guide->id);
                    $this->db->execute();

                    // Delete from guide_reviews
                    $this->db->query('DELETE FROM guide_reviews WHERE guide_id = :guide_id');
                    $this->db->bind(':guide_id', $guide->id);
                    $this->db->execute();

                    // Delete from guide_profiles
                    $this->db->query('DELETE FROM guide_profiles WHERE user_id = :user_id');
                    $this->db->bind(':user_id', $userId);
                    $this->db->execute();
                }
            }

            // Delete from user_preferences
            $this->db->query('DELETE FROM user_preferences WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // Delete from account_recovery
            $this->db->query('DELETE FROM account_recovery WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            // Finally delete the user
            $this->db->query('DELETE FROM users WHERE id = :id');
            $this->db->bind(':id', $userId);
            $this->db->execute();

            // Commit transaction
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            error_log('Error deleting account: ' . $e->getMessage());
            return false;
        }
    }

    public function updateUser($id, $name, $email)
    {
        $this->db->query("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteUser($id)
    {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    public function updateName($userId, $name)
    {
        $this->db->query('UPDATE users SET name = :name WHERE id = :id');
        $this->db->bind(':name', $name);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
    public function updateProfileImage($userId, $profile_image)
    {
        $this->db->query('UPDATE users SET profile_image = :profile_image WHERE id = :id');
        $this->db->bind(':profile_image', $profile_image);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    /**

     * Verify reset token
     * 
     * @param string $token Reset token
     * @return bool
     */
    public function verifyResetToken($token) {
        // Log the token being verified
        error_log("Verifying token: " . $token);
        
        $this->db->query('SELECT id, reset_token, reset_token_expires FROM users WHERE reset_token = :token AND reset_token_expires > NOW()');
        $this->db->bind(':token', $token);
        
        $row = $this->db->single();
        
        // Log the result
        if ($row) {
            error_log("Token found - ID: " . $row->id . ", Expires: " . $row->reset_token_expires);
        } else {
            error_log("Token not found or expired");
        }
        
        return $row ? true : false;
    }
    
    /**
     * Save password reset token
     * 
     * @param int $userId User ID
     * @param string $token Reset token
     * @param string $expires Expiration date
     * @return bool
     */
    public function savePasswordResetToken($userId, $token, $expires) {
        // Log the token being saved
        error_log("Saving token for user $userId: $token, expires: $expires");
        
        $this->db->query('UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE id = :id');
        
        // Bind values
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        $this->db->bind(':id', $userId);
        
        // Execute
        $result = $this->db->execute();
        
        // Log the result
        error_log("Token save result: " . ($result ? "success" : "failed"));
        
        return $result;
    }
    
    /**
     * Reset password
     * 
     * @param string $token Reset token
     * @param string $password New password
     * @return bool
     */
    public function resetPassword($token, $password) {
        // Log the reset attempt
        error_log("Attempting to reset password with token: " . $token);
        
        // Get user ID from token
        $this->db->query('SELECT id, reset_token, reset_token_expires FROM users WHERE reset_token = :token AND reset_token_expires > NOW() AND reset_token IS NOT NULL');
        $this->db->bind(':token', $token);
        
        $row = $this->db->single();
        
        if (!$row) {
            error_log("No valid user found for token: " . $token);
            return false;
        }
        
        error_log("Found user ID: " . $row->id . " for token: " . $token);
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and clear reset token
        $this->db->query('UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id');
        
        // Bind values
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $row->id);
        
        // Execute
        $result = $this->db->execute();
        
        // Log the result
        error_log("Password reset " . ($result ? "successful" : "failed") . " for user ID: " . $row->id);
        
        return $result;

    }
}