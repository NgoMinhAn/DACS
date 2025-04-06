<?php
/**
 * Session Helper
 * Handles session-related functionality, especially flash messages
 */
class SessionHelper {
    /**
     * Set a flash message in the session
     *
     * @param string $type Message type (success, error, warning, info)
     * @param string $message The message text
     * @return void
     */
    public static function setFlash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Set an array of error messages
     *
     * @param array $errors Array of error messages
     * @return void
     */
    public static function setErrors($errors) {
        $_SESSION['errors'] = $errors;
    }
    
    /**
     * Set old input data (for form repopulation after validation errors)
     *
     * @param array $data Form data
     * @return void
     */
    public static function setOldInput($data) {
        $_SESSION['old_input'] = $data;
    }
    
    /**
     * Get a value from old input
     *
     * @param string $key Key to retrieve
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public static function getOldInput($key, $default = '') {
        return $_SESSION['old_input'][$key] ?? $default;
    }
    
    /**
     * Check if old input has a key
     *
     * @param string $key Key to check
     * @return bool
     */
    public static function hasOldInput($key) {
        return isset($_SESSION['old_input'][$key]);
    }
    
    /**
     * Display flash messages and clear them
     *
     * @return string HTML for displaying flash messages
     */
    public static function displayFlash() {
        $html = '';
        
        // Process single success/error messages
        if (isset($_SESSION['success'])) {
            $html .= '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        
        if (isset($_SESSION['error'])) {
            $html .= '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        
        // Process error arrays
        if (isset($_SESSION['errors']) && is_array($_SESSION['errors']) && !empty($_SESSION['errors'])) {
            $html .= '<div class="alert alert-danger"><ul class="mb-0">';
            foreach ($_SESSION['errors'] as $error) {
                $html .= '<li>' . $error . '</li>';
            }
            $html .= '</ul></div>';
            unset($_SESSION['errors']);
        }
        
        // Process flash messages
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $message) {
                $alertClass = 'alert-info';
                switch ($type) {
                    case 'success':
                        $alertClass = 'alert-success';
                        break;
                    case 'error':
                        $alertClass = 'alert-danger';
                        break;
                    case 'warning':
                        $alertClass = 'alert-warning';
                        break;
                }
                
                $html .= '<div class="alert ' . $alertClass . '">' . $message . '</div>';
            }
            
            unset($_SESSION['flash']);
        }
        
        // Clear old input after displaying messages
        if (isset($_SESSION['old_input'])) {
            unset($_SESSION['old_input']);
        }
        
        return $html;
    }
} 