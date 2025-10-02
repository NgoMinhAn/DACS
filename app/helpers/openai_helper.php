<?php
/**
 * Helper for OpenAI API integration (using openai-php/client)
 * https://github.com/openai-php/client
 */
require_once __DIR__ . '/../../vendor/autoload.php';

function getOpenAIRecommendations($userHobbies, $pastGuides, $apiKey) {
    // Use OpenAI::client static method as per library docs
    $client = \OpenAI::client($apiKey);

    $prompt = "Given the following user hobbies: " . implode(", ", $userHobbies) . ". ";
    if (!empty($pastGuides)) {
        $prompt .= "The user has previously chosen these tour guides: " . implode(", ", $pastGuides) . ". ";
    }
    $prompt .= "Recommend 3 suitable tour guides from the available list, and explain why they are a good fit.";

    try {
        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful AI assistant for a tour guide platform.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 400,
            'temperature' => 0.7,
        ]);
        if (isset($result['choices'][0]['message']['content'])) {
            return $result['choices'][0]['message']['content'];
        }
        return 'OpenAI API: No recommendation returned.';
    } catch (Exception $e) {
        return 'OpenAI API error: ' . $e->getMessage();
    }
}
