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

if (!function_exists('getLocale')) {
    /**
     * Get current locale
     * Checks session, then cookie, defaults to 'en'
     *
     * @return string 'en' or 'vi'
     */
    function getLocale() {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        if (!empty($_SESSION['locale'])) {
            return $_SESSION['locale'];
        }
        if (!empty($_COOKIE['locale'])) {
            return $_COOKIE['locale'];
        }
        return 'en';
    }
}

if (!function_exists('site_set_locale')) {
    /**
     * Set current locale (stores in session and cookie)
     *
     * @param string $lang 'en' or 'vi'
     * @return void
     */
    function site_set_locale($lang) {
        $allowed = ['en', 'vi'];
        $lang = in_array($lang, $allowed) ? $lang : 'en';
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $_SESSION['locale'] = $lang;
        setcookie('locale', $lang, time() + (365 * 24 * 60 * 60), '/');
    }
}

/**
 * Load translation array for a locale (cached in global)
 *
 * @param string $locale
 * @return array
 */
if (!function_exists('loadTranslations')) {
    function loadTranslations($locale = null) {
    global $translations;
    if ($locale === null) {
        $locale = getLocale();
    }
    $locale = in_array($locale, ['en', 'vi']) ? $locale : 'en';

    if (!isset($translations)) {
        $translations = [];
    }

    if (!empty($translations[$locale])) {
        return $translations[$locale];
    }

    $langFile = APP_PATH . '/lang/' . $locale . '.php';
    if (file_exists($langFile)) {
        $trans = require $langFile;
        if (is_array($trans)) {
            $translations[$locale] = $trans;
            return $trans;
        }
    }

    // Fallback to empty
    $translations[$locale] = [];
    return $translations[$locale];
    }
}

/**
 * Translate a key using loaded translations. Supports dot notation.
 *
 * @param string $key
 * @param array $replace replacements for placeholders like :name
 * @return string
 */
if (!function_exists('__')) {
    function __($key, $replace = []) {
    $locale = getLocale();
    $trans = loadTranslations($locale);

    $segments = explode('.', $key);
    $value = $trans;
    foreach ($segments as $seg) {
        if (is_array($value) && array_key_exists($seg, $value)) {
            $value = $value[$seg];
        } else {
            $value = null;
            break;
        }
    }

    if ($value === null) {
        // Attempt English fallback
        $en = loadTranslations('en');
        $value = $en;
        foreach ($segments as $seg) {
            if (is_array($value) && array_key_exists($seg, $value)) {
                $value = $value[$seg];
            } else {
                $value = $key; // final fallback: return key
                break;
            }
        }
    }

    // Replace placeholders
    if (!empty($replace) && is_string($value)) {
        foreach ($replace as $k => $v) {
            $value = str_replace(':' . $k, $v, $value);
        }
    }

        return is_string($value) ? $value : $key;
    }
}
