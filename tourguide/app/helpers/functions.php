<?php
/**
 * Helper Functions for Tour Guide Website
 */

/**
 * Redirects to the specified page
 * 
 * @param string $page The page to redirect to
 * @return void
 */
function redirect($page) {
    header('Location: ' . URL_ROOT . '/' . $page);
    exit;
}

/**
 * Creates a URL with the site root
 * 
 * @param string $path The path to append to the URL root
 * @return string The complete URL
 */
function url($path = '') {
    return URL_ROOT . '/' . $path;
}

/**
 * Sanitizes user input
 * 
 * @param string $data The data to sanitize
 * @return string The sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Displays a flash message
 * 
 * @param string $name The name of the message
 * @param string $message The message content
 * @param string $class The CSS class for styling
 * @return void
 */
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

/**
 * Checks if user is logged in
 * 
 * @return boolean Whether the user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Checks if user is a guide
 * 
 * @return boolean Whether the user is a guide
 */
function isGuide() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'guide';
}

/**
 * Format date for display
 * 
 * @param string $date The date to format
 * @param string $format The format to use
 * @return string The formatted date
 */
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Get global site configuration
 * 
 * @param string $key The configuration key to retrieve
 * @return mixed The configuration value
 */
function getConfig($key = null) {
    global $config;
    
    if ($key === null) {
        return $config;
    }
    
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return null;
        }
    }
    
    return $value;
} 