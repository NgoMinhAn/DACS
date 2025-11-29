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
    $prompt .= "Keep the conversation natural and helpful, and only ask for more info if needed. Try to ask as little as possible and recommend the tourguides after each question. If they don't tell you anything related to tourguide then just act like a normal helpful AI.\n";
    $prompt .= "Remember to use the user's language. For example, if they ask you in vietnamese, keep answering in vietnamese\n";
    try {
        $modelId = 'gemini-2.5-flash-lite';
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

/**
 * Get recommended guide IDs based on user hobbies using Gemini AI
 * 
 * @param string $userHobbies The user's hobbies from their bio
 * @param array $allGuides Array of all available guides
 * @param int $limit Maximum number of guides to return
 * @param string|null $apiKey Gemini API key (optional, will use GEMINI_API_KEY constant if not provided)
 * @return array Array of guide IDs that match the user's hobbies
 */
function getRecommendedGuidesByHobbies($userHobbies, $allGuides, $limit = 6, $apiKey = null) {
    // Use API key from constant if not provided
    if (empty($apiKey)) {
        $apiKey = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : getenv('GEMINI_API_KEY');
    }
    
    if (empty($apiKey)) {
        error_log('Gemini API error: missing API key for hobby-based recommendations');
        return [];
    }

    if (empty($userHobbies) || trim($userHobbies) === '') {
        return [];
    }

    // Prepare guide summaries with IDs for the prompt
    $guideSummaries = [];
    foreach ($allGuides as $g) {
        $guideId = $g->guide_id ?? $g->id ?? null;
        if ($guideId) {
            $guideSummaries[] = "ID: {$guideId}, Name: {$g->name}, Bio: " . substr($g->bio ?? '', 0, 200) . ", Specialties: {$g->specialties}, Location: {$g->location}, Rating: {$g->avg_rating}";
        }
    }
    
    if (empty($guideSummaries)) {
        return [];
    }
    
    $guidesText = implode("\n", $guideSummaries);

    $client = new Client($apiKey);

    $prompt = "You are a tour guide recommendation system. Based on a user's hobbies, recommend the most suitable tour guides.\n\n";
    $prompt .= "User's hobbies: {$userHobbies}\n\n";
    $prompt .= "Available tour guides:\n{$guidesText}\n\n";
    $prompt .= "Analyze the user's hobbies and match them with tour guides based on:\n";
    $prompt .= "1. Guide specialties that align with the hobbies\n";
    $prompt .= "2. Guide bio content that relates to the hobbies\n";
    $prompt .= "3. Guide location if relevant to the hobbies\n\n";
    $prompt .= "Return ONLY a JSON array of guide IDs (numbers only) in order of best match to least match.\n";
    $prompt .= "Return maximum {$limit} guide IDs.\n";
    $prompt .= "Format: [1, 2, 3, 4, 5, 6]\n";
    $prompt .= "Do not include any explanation, only the JSON array.";

    try {
        $modelId = 'gemini-2.5-flash';
        $response = $client->generativeModel($modelId)->generateContent(
            new TextPart($prompt)
        );

        // Extract text from response
        $text = '';
        if (is_string($response)) {
            $text = $response;
        } elseif (method_exists($response, 'text')) {
            $text = $response->text();
        } elseif (is_object($response) && property_exists($response, 'content')) {
            $text = $response->content;
        }

        if (empty($text)) {
            error_log('Gemini API: Blank response for hobby-based recommendations');
            return [];
        }

        // Clean the response - remove markdown code blocks if present
        $text = trim($text);
        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/^```\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        $text = trim($text);

        // Try to extract JSON array from the response
        if (preg_match('/\[[\d\s,]+\]/', $text, $matches)) {
            $jsonText = $matches[0];
        } else {
            $jsonText = $text;
        }

        // Parse JSON
        $guideIds = json_decode($jsonText, true);
        
        if (!is_array($guideIds)) {
            error_log('Gemini API: Failed to parse guide IDs from response: ' . $text);
            return [];
        }

        // Filter to ensure we only return valid integers and limit the results
        $guideIds = array_filter(array_map('intval', $guideIds));
        $guideIds = array_slice($guideIds, 0, $limit);

        return array_values($guideIds);
    } catch (\Exception $e) {
        error_log('Gemini API error for hobby-based recommendations: ' . $e->getMessage());
        return [];
    }
}