<?php
/**
 * Auto Find and Translate Script
 * Tự động tìm text hardcode trong views, thêm vào en.php và dịch sang vi.php
 * 
 * Usage: php scripts/auto_find_and_translate.php [--dry-run]
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

echo "=== Auto Find and Translate Script ===\n";
echo "Tự động tìm text hardcode và dịch\n\n";

if ($dryRun) {
    echo "DRY RUN MODE - No changes will be saved\n\n";
}

// Step 1: Find hardcoded texts
echo "Step 1: Đang quét views để tìm text hardcode...\n";
$hardcodedTexts = findHardcodedTexts(APP_PATH . '/views');

$totalTexts = 0;
foreach ($hardcodedTexts as $file => $texts) {
    $totalTexts += count($texts);
}

echo "Tìm thấy {$totalTexts} text hardcode trong " . count($hardcodedTexts) . " files\n\n";

if ($totalTexts == 0) {
    echo "Không tìm thấy text hardcode nào. Có thể tất cả đã dùng translation keys.\n";
    exit(0);
}

// Step 2: Load current translations
$enFile = APP_PATH . '/lang/en.php';
$enTranslations = require $enFile;

// Step 3: Add new keys to en.php
echo "Step 2: Đang thêm keys mới vào en.php...\n";
$newKeys = 0;

if (!$dryRun) {
    // Initialize 'auto' section if not exists
    if (!isset($enTranslations['auto'])) {
        $enTranslations['auto'] = [];
    }
    
    foreach ($hardcodedTexts as $file => $texts) {
        foreach ($texts as $text => $key) {
            // Check if key already exists
            $fullKey = $key;
            if (getNestedValue($enTranslations, $fullKey) === null) {
                $enTranslations = setNestedValue($enTranslations, $fullKey, $text);
                $newKeys++;
                echo "  + Thêm: {$fullKey} = \"{$text}\"\n";
            }
        }
    }
    
    if ($newKeys > 0) {
        // Save en.php
        $output = "<?php\n";
        $output .= "return " . var_export($enTranslations, true) . ";\n";
        file_put_contents($enFile, $output);
        echo "\n✓ Đã thêm {$newKeys} keys mới vào en.php\n\n";
    } else {
        echo "Không có keys mới cần thêm.\n\n";
    }
} else {
    echo "[DRY RUN] Sẽ thêm các keys sau vào en.php:\n";
    foreach ($hardcodedTexts as $file => $texts) {
        foreach ($texts as $text => $key) {
            echo "  - {$key} = \"{$text}\"\n";
        }
    }
    echo "\n";
}

// Step 4: Translate missing keys
echo "Step 3: Đang dịch các keys còn thiếu...\n";
$stats = autoTranslateAllMissingKeys($dryRun);

// Display results
foreach ($stats['messages'] as $message) {
    echo $message . "\n";
}

echo "\n=== Summary ===\n";
echo "Hardcoded texts found: {$totalTexts}\n";
echo "New keys added: {$newKeys}\n";
echo "Total English keys: {$stats['total_en_keys']}\n";
echo "Total Vietnamese keys: {$stats['total_vi_keys']}\n";
echo "Missing keys: {$stats['missing']}\n";
echo "Translated: {$stats['translated']}\n";
echo "Errors: {$stats['errors']}\n";

if ($dryRun) {
    echo "\nRun without --dry-run to apply changes.\n";
} elseif ($stats['translated'] > 0 || $newKeys > 0) {
    echo "\n✓ Hoàn thành!\n";
    echo "Lưu ý: Bạn cần thủ công thay thế text hardcode trong views bằng __('key')\n";
}


