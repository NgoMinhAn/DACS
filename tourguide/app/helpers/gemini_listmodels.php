<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use GeminiAPI\Client;

function geminiListModels($apiKey) {
    $client = new Client($apiKey);
    try {
        $response = $client->listModels();
        return $response->models;
    } catch (Exception $e) {
        return 'Gemini API error: ' . $e->getMessage();
    }
}

// Run and print available models for debug
$models = geminiListModels(GEMINI_API_KEY);
if (is_array($models)) {
    echo "Available Gemini Models:\n";
    foreach ($models as $model) {
        echo $model->name . "\n";
    }
} else {
    echo $models; // error message
}
