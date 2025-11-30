<?php
/**
 * Auto Translation Script
 * Translates all missing translation keys from English to Vietnamese
 * 
 * Usage: php scripts/auto_translate.php [--dry-run]
 */

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// Load configuration
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/helpers/functions.php';
require_once APP_PATH . '/helpers/translation_helper.php';

// Check for dry-run flag
$dryRun = in_array('--dry-run', $argv) || in_array('-d', $argv);

echo "=== Auto Translation Script ===\n";
echo "Translating all missing keys from English to Vietnamese\n\n";

if ($dryRun) {
    echo "DRY RUN MODE - No changes will be saved\n\n";
}

// Run auto-translation
$stats = autoTranslateAllMissingKeys($dryRun);

// Display messages
foreach ($stats['messages'] as $message) {
    echo $message . "\n";
}

// Display summary
echo "\n=== Summary ===\n";
echo "Total English keys: {$stats['total_en_keys']}\n";
echo "Total Vietnamese keys: {$stats['total_vi_keys']}\n";
echo "Missing keys: {$stats['missing']}\n";
echo "Translated: {$stats['translated']}\n";
echo "Errors: {$stats['errors']}\n";

if ($dryRun) {
    echo "\nRun without --dry-run to apply translations.\n";
} elseif ($stats['translated'] > 0) {
    echo "\n✓ Translation completed successfully!\n";
}


