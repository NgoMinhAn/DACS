<?php

class MessageController
{
    private $messageModel;

    public function __construct()
    {
        if (!isLoggedIn()) {
            $this->jsonResponse(false, 'Unauthorized', 401);
        }

        $this->messageModel = new MessageModel();
    }

    public function markDelivered()
    {
        $this->ensurePost();
        $payload = $this->getJsonPayload();

        $messageId = isset($payload['message_id']) ? (int) $payload['message_id'] : 0;
        if ($messageId <= 0) {
            $this->jsonResponse(false, 'Invalid message ID', 400);
        }

        $message = $this->messageModel->getMessageWithContext($messageId);
        if (!$message || !$this->canAccessMessage($message)) {
            $this->jsonResponse(false, 'Forbidden', 403);
        }

        if ((int) $message->sender_id === (int) $_SESSION['user_id']) {
            $this->jsonResponse(true, 'Nothing to update', 200, [
                'delivered_at' => $message->delivered_at,
            ]);
        }

        $updated = $this->messageModel->markDelivered($messageId);
        if (!$updated) {
            $this->jsonResponse(false, 'Unable to update', 500);
        }

        broadcast_realtime_message($message->booking_id, [
            'message_id'   => (int) $messageId,
            'status'       => 'delivered',
            'timestamp'    => $updated->delivered_at,
        ], 'messageStatus');

        $this->jsonResponse(true, 'Delivered updated', 200, [
            'delivered_at' => $updated->delivered_at,
        ]);
    }

    public function markRead()
    {
        $this->ensurePost();
        $payload = $this->getJsonPayload();

        $messageId = isset($payload['message_id']) ? (int) $payload['message_id'] : 0;
        if ($messageId <= 0) {
            $this->jsonResponse(false, 'Invalid message ID', 400);
        }

        $message = $this->messageModel->getMessageWithContext($messageId);
        if (!$message || !$this->canAccessMessage($message)) {
            $this->jsonResponse(false, 'Forbidden', 403);
        }

        if ((int) $message->sender_id === (int) $_SESSION['user_id']) {
            $this->jsonResponse(true, 'Nothing to update', 200, [
                'read_at' => $message->read_at,
            ]);
        }

        $updated = $this->messageModel->markRead($messageId);
        if (!$updated) {
            $this->jsonResponse(false, 'Unable to update', 500);
        }

        broadcast_realtime_message($message->booking_id, [
            'message_id'   => (int) $messageId,
            'status'       => 'read',
            'timestamp'    => $updated->read_at,
        ], 'messageStatus');

        $this->jsonResponse(true, 'Read updated', 200, [
            'read_at' => $updated->read_at,
        ]);
    }

    private function canAccessMessage($message)
    {
        $userId = (int) $_SESSION['user_id'];
        return $userId === (int) $message->booking_user_id || $userId === (int) $message->guide_user_id;
    }

    private function ensurePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Method Not Allowed', 405);
        }
    }

    private function getJsonPayload()
    {
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return $_POST;
    }

    private function jsonResponse($success, $message, $statusCode = 200, $data = [])
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ]);
        exit;
    }
}


