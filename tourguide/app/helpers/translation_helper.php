<?php
/**
 * Translation Helper
 * Automatically translate all missing translation keys using Gemini API
 */

require_once __DIR__ . '/../../vendor/autoload.php';
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

// Fix SSL certificate issue for Laragon/Windows
if (!ini_get('curl.cainfo') && !getenv('SSL_CERT_FILE')) {
    // Try to find cacert.pem in common locations
    $cacertPaths = [
        __DIR__ . '/../../vendor/guzzlehttp/guzzle/src/cacert.pem',
        __DIR__ . '/../../cacert.pem',
        'C:/laragon/bin/php/php-8.1.10-Win32-vs16-x64/extras/ssl/cacert.pem',
    ];
    
    foreach ($cacertPaths as $path) {
        if (file_exists($path)) {
            putenv('SSL_CERT_FILE=' . $path);
            break;
        }
    }
    
    // If still not found, disable SSL verification for development (NOT for production!)
    if (!getenv('SSL_CERT_FILE')) {
        // This is a workaround for development only
        // In production, you should properly configure SSL certificates
    }
}

/**
 * Translate text from English to Vietnamese using Gemini API
 * 
 * @param string $text English text to translate
 * @param string|null $apiKey Gemini API key
 * @return string Translated Vietnamese text
 */
function translateToVietnamese($text, $apiKey = null) {
    if (empty($apiKey)) {
        $apiKey = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : getenv('GEMINI_API_KEY');
    }
    
    if (empty($apiKey)) {
        error_log('Translation error: missing Gemini API key');
        return $text;
    }

    try {
        // Create HTTP client with SSL verification disabled for development (Laragon/Windows issue)
        // WARNING: Only use this in development, not production!
        $httpClient = new \GuzzleHttp\Client([
            'verify' => false, // Disable SSL verification for development
        ]);
        
        $client = new Client($apiKey, $httpClient);

        $prompt = "Translate the following English text to Vietnamese. ";
        $prompt .= "Keep it natural and appropriate for a tour guide website. ";
        $prompt .= "Do not add any explanation, only return the translated text.\n\n";
        $prompt .= "English: {$text}\n";
        $prompt .= "Vietnamese:";

        $modelId = 'gemini-2.5-flash-lite';
        $response = $client->generativeModel($modelId)->generateContent(
            new TextPart($prompt)
        );

        // Extract text from response (same method as gemini_helper.php)
        $translated = '';
        if (is_string($response)) {
            $translated = $response;
        } elseif (method_exists($response, 'text')) {
            $translated = $response->text();
        } elseif (is_object($response) && property_exists($response, 'content')) {
            $translated = $response->content;
        } else {
            // Debug: log the response structure
            error_log('Translation debug: unexpected response shape. Type: ' . gettype($response));
            if (is_object($response)) {
                error_log('Translation debug: response class: ' . get_class($response));
                error_log('Translation debug: response methods: ' . implode(', ', get_class_methods($response)));
            }
        }

        $translated = trim($translated);
        // Remove any prefix like "Vietnamese:" if present
        $translated = preg_replace('/^Vietnamese:\s*/i', '', $translated);
        $translated = trim($translated);

        // If still empty or same, return original
        if (empty($translated) || $translated === $text) {
            error_log('Translation warning: API returned empty or same text for: ' . $text);
            return $text;
        }

        return $translated;
    } catch (\Exception $e) {
        error_log('Translation error: ' . $e->getMessage());
        error_log('Translation error trace: ' . $e->getTraceAsString());
        return $text;
    }
}

/**
 * Get nested array value by dot notation key
 */
function getNestedValue($array, $key) {
    $segments = explode('.', $key);
    $value = $array;
    
    foreach ($segments as $seg) {
        if (is_array($value) && array_key_exists($seg, $value)) {
            $value = $value[$seg];
        } else {
            return null;
        }
    }
    
    return $value;
}

/**
 * Set nested array value by dot notation key
 */
function setNestedValue($array, $key, $value) {
    $segments = explode('.', $key);
    $current = &$array;
    
    foreach ($segments as $seg) {
        if (!isset($current[$seg]) || !is_array($current[$seg])) {
            $current[$seg] = [];
        }
        $current = &$current[$seg];
    }
    
    $current = $value;
    return $array;
}

/**
 * Flatten array to dot notation keys
 */
function flattenArray($array, $prefix = '') {
    $result = [];
    
    foreach ($array as $key => $value) {
        $newKey = $prefix ? $prefix . '.' . $key : $key;
        
        if (is_array($value)) {
            $result = array_merge($result, flattenArray($value, $newKey));
        } else {
            $result[$newKey] = $value;
        }
    }
    
    return $result;
}

/**
 * Find hardcoded English text in view files that should be translated
 * Returns array of [file => [text => suggested_key]]
 */
function findHardcodedTexts($directory) {
    $hardcodedTexts = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            
            // Skip files that are not views (skip helpers, models, controllers)
            if (strpos($file->getPathname(), '/helpers/') !== false ||
                strpos($file->getPathname(), '/models/') !== false ||
                strpos($file->getPathname(), '/controllers/') !== false) {
                continue;
            }
            
            // Find text that looks like English but is not already using __()
            // Pattern: >Text< or "Text" or 'Text' that is not inside __()
            // This is a simple heuristic - may need refinement
            
            // Find text in HTML tags: >English Text<
            preg_match_all('/>([A-Z][^<]{3,50})</', $content, $matches);
            foreach ($matches[1] as $text) {
                $text = trim($text);
                // Skip if already using __() or contains PHP code
                if (strpos($text, '__(') !== false || 
                    strpos($text, '<?php') !== false ||
                    strpos($text, '$') !== false ||
                    strpos($text, 'echo') !== false ||
                    preg_match('/^[A-Z][a-z]+ [A-Z][a-z]+/', $text)) { // Looks like English sentence
                    
                    // Generate a key name from the text
                    $key = generateKeyName($text);
                    if (!empty($key) && strlen($text) > 3) {
                        $hardcodedTexts[$file->getPathname()][$text] = $key;
                    }
                }
            }
        }
    }

    return $hardcodedTexts;
}

/**
 * Generate a translation key name from text
 */
function generateKeyName($text) {
    // Convert "How It Works" -> "how_it_works"
    $key = strtolower($text);
    $key = preg_replace('/[^a-z0-9\s]/', '', $key);
    $key = preg_replace('/\s+/', '_', $key);
    $key = trim($key, '_');
    
    // Limit length
    if (strlen($key) > 50) {
        $key = substr($key, 0, 50);
    }
    
    return !empty($key) ? 'auto.' . $key : null;
}

/**
 * Auto-translate all missing keys in Vietnamese translation file
 * 
 * @param bool $dryRun If true, only show what would be translated without saving
 * @return array Statistics about the translation process
 */
function autoTranslateAllMissingKeys($dryRun = false) {
    $stats = [
        'total_en_keys' => 0,
        'total_vi_keys' => 0,
        'missing' => 0,
        'translated' => 0,
        'errors' => 0,
        'messages' => []
    ];

    // Load English translations (source)
    $enFile = APP_PATH . '/lang/en.php';
    if (!file_exists($enFile)) {
        $stats['messages'][] = 'English translation file not found';
        return $stats;
    }
    
    $enTranslations = require $enFile;
    if (!is_array($enTranslations)) {
        $stats['messages'][] = 'Invalid English translation file';
        return $stats;
    }

    // Load Vietnamese translations (target)
    $viFile = APP_PATH . '/lang/vi.php';
    $viTranslations = [];
    if (file_exists($viFile)) {
        $viTranslations = require $viFile;
        if (!is_array($viTranslations)) {
            $viTranslations = [];
        }
    }

    // Flatten both arrays to compare keys
    $enFlat = flattenArray($enTranslations);
    $viFlat = flattenArray($viTranslations);
    
    $stats['total_en_keys'] = count($enFlat);
    $stats['total_vi_keys'] = count($viFlat);

    // Find missing keys (only string values)
    $missingKeys = [];
    foreach ($enFlat as $key => $enValue) {
        if (!isset($viFlat[$key]) && is_string($enValue) && !empty(trim($enValue))) {
            $missingKeys[$key] = $enValue;
            $stats['missing']++;
        }
    }

    if (empty($missingKeys)) {
        $stats['messages'][] = 'No missing translations found! All keys are already translated.';
        return $stats;
    }

    $stats['messages'][] = "Found " . count($missingKeys) . " missing translation keys.";

    // Get API key
    $apiKey = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : getenv('GEMINI_API_KEY');
    if (empty($apiKey)) {
        $stats['messages'][] = 'Warning: GEMINI_API_KEY not found. Cannot auto-translate.';
        return $stats;
    }

    $stats['messages'][] = "Starting translation of " . count($missingKeys) . " keys...";

    // Translate all missing keys
    $translatedCount = 0;
    foreach ($missingKeys as $key => $enText) {
        if ($dryRun) {
            $translatedCount++;
            continue;
        }

        $viText = translateToVietnamese($enText, $apiKey);
        
        if ($viText !== $enText && !empty(trim($viText))) {
            // Set the translated value in the nested structure
            $viTranslations = setNestedValue($viTranslations, $key, $viText);
            $translatedCount++;
            
            // Small delay to avoid rate limiting
            usleep(300000); // 0.3 seconds
        } else {
            $stats['errors']++;
        }
    }

    $stats['translated'] = $translatedCount;

    // Save Vietnamese translations
    if (!$dryRun && $stats['translated'] > 0) {
        $output = "<?php\n";
        $output .= "/**\n";
        $output .= " * Vietnamese Translation File\n";
        $output .= " * Auto-generated on " . date('Y-m-d H:i:s') . "\n";
        $output .= " */\n\n";
        $output .= "return " . var_export($viTranslations, true) . ";\n";
        
        if (file_put_contents($viFile, $output)) {
            $stats['messages'][] = "✓ Successfully saved " . $stats['translated'] . " translations to {$viFile}";
        } else {
            $stats['messages'][] = "✗ Failed to save translations file";
            $stats['errors']++;
        }
    } elseif ($dryRun) {
        $stats['messages'][] = "[DRY RUN] Would translate " . count($missingKeys) . " keys.";
    }

    return $stats;
}
