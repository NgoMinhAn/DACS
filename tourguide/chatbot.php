<?php
// chatbot.php - Backend handler for AI chat
header('Content-Type: application/json');
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/helpers/gemini_helper.php';

// Get user message from POST JSON
$input = json_decode(file_get_contents('php://input'), true);
$message = isset($input['message']) ? trim($input['message']) : '';

if (!$message) {
    echo json_encode(['reply' => 'Please enter a message.']);
    exit;
}


// Optionally, you can add user context here (e.g., hobbies, user id)
$reply = getGeminiRecommendations([$message], [], GEMINI_API_KEY);

// Debug: include raw AI response and PHP errors if any
$debug = [];
if (is_array($reply) || is_object($reply)) {
    $debug['raw'] = print_r($reply, true);
}
if (!$reply) {
    $reply = 'Sorry, I could not generate a response.';
}

// Optionally, include PHP error log (for local dev only)
if (function_exists('error_get_last')) {
    $err = error_get_last();
    if ($err) $debug['php_error'] = $err;
}

$response = ['reply' => nl2br(htmlspecialchars($reply))];
if (!empty($debug)) $response['debug'] = $debug;
echo json_encode($response);
