<?php
/**
 * Helper functions for realtime messaging.
 */

if (!function_exists('broadcast_realtime_message')) {
    /**
     * Broadcast a message payload to the realtime server.
     *
     * @param int   $bookingId  The booking room identifier.
     * @param array $message    The message payload (sender_id, message, created_at, etc.).
     * @return void
     */
    function broadcast_realtime_message($bookingId, array $data, $event = 'message')
    {
        if (empty(REALTIME_SERVER_URL) || empty(REALTIME_BROADCAST_TOKEN)) {
            return;
        }

        $endpoint = rtrim(REALTIME_SERVER_URL, '/') . '/broadcast';

        $payload = [
            'bookingId' => (int) $bookingId,
            'event'     => $event,
            'data'      => $data,
        ];

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . REALTIME_BROADCAST_TOKEN,
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT        => 3,
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
}

if (!function_exists('generate_realtime_signature')) {
    /**
     * Generate HMAC signature for joining a realtime booking room.
     *
     * @param int $bookingId
     * @param int $userId
     * @return string|null
     */
    function generate_realtime_signature($bookingId, $userId)
    {
        if (empty(REALTIME_AUTH_SECRET)) {
            return null;
        }

        $payload = $bookingId . '|' . $userId;

        return hash_hmac('sha256', $payload, REALTIME_AUTH_SECRET);
    }
}

