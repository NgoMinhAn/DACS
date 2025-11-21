<?php
/**
 * Locale Controller
 * Simple controller to change site language (locale)
 */
class LocaleController {
    public function set($lang = null) {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        // Accept language from URL segment or GET/POST
        if (!$lang) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $lang = $_POST['language'] ?? null;
            } else {
                $lang = $_GET['lang'] ?? null;
            }
        }

        require_once HELPER_PATH . '/functions.php';

        if ($lang) {
            if (function_exists('site_set_locale')) {
                site_set_locale($lang);
            }
        }

        // Redirect back if possible
        $redirect = $_SERVER['HTTP_REFERER'] ?? URL_ROOT;
        header('Location: ' . $redirect);
        exit;
    }
}
