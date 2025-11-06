<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;



function getGeminiRecommendations(array $userMessages, array $pastGuides = [], ?string $apiKey = null) {
    // Prefer env var if API key not passed explicitly
    $apiKey = $apiKey ?: getenv('GEMINI_API_KEY');
    if (empty($apiKey)) {
        return 'Gemini API error: missing API key. Set GEMINI_API_KEY environment variable or pass $apiKey.';
    }

    // Fetch tour guide data from the database
    require_once __DIR__ . '/../models/GuideModel.php';
    require_once __DIR__ . '/../config/database.php';
    $guideModel = new \GuideModel();
    $guides = $guideModel->getAllGuides();

    // Prepare a summary of guides for the prompt (limit to 10 for token efficiency)
    $guideSummaries = [];
    $maxGuides = 10;
    foreach (array_slice($guides, 0, $maxGuides) as $g) {
        $guideSummaries[] = "Name: {$g->name}, Languages: {$g->languages}, Specialties: {$g->specialties}, Rating: {$g->avg_rating}, Price: {$g->hourly_rate}";
    }
    $guidesText = implode("\n", $guideSummaries);

    $client = new Client($apiKey);

    // Use the last user message as the current input
    $userInput = end($userMessages);

    $prompt = "You are a friendly, conversational AI tour guide assistant.\n";
    $prompt .= "Here is a list of available tour guides (each with name, languages, specialties, rating, and price):\n" . $guidesText . "\n";
    $prompt .= "The user says: '" . $userInput . "'\n";
    $prompt .= "If you do not have enough information about the user's interests, ask a friendly follow-up question to learn more (such as their hobbies, preferred activities, or travel style).\n";
    $prompt .= "If you have enough information, recommend 3 suitable tour guides from the list above, and explain why they are a good fit.\n";
    $prompt .= "Keep the conversation natural and helpful, and only ask for more info if needed.";

    try {
        $modelId = 'gemini-2.5-flash';
        $response = $client->generativeModel($modelId)->generateContent(
            new TextPart($prompt)
        );

        if (is_string($response)) {
            $text = $response;
        } elseif (method_exists($response, 'text')) {
            $text = $response->text();
        } elseif (is_object($response) && property_exists($response, 'content')) {
            $text = $response->content;
        } else {
            return 'Gemini API debug: unexpected response shape. Raw: ' . print_r($response, true);
        }

        if (empty($text)) {
            return 'Gemini API debug: Blank response. Raw: ' . print_r($response, true);
        }

        return $text;
    } catch (\Exception $e) {
        $msg = 'Gemini API error: ' . $e->getMessage();
        return $msg;
    }
}
