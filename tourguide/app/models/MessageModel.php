<?php
class MessageModel {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function sendMessage($bookingId, $senderId, $message) {
        $timestamp = date('Y-m-d H:i:s');

        $this->db->query("INSERT INTO messages (booking_id, sender_id, message, created_at) VALUES (:booking_id, :sender_id, :message, :created_at)");
        $this->db->bind(':booking_id', $bookingId);
        $this->db->bind(':sender_id', $senderId);
        $this->db->bind(':message', $message);
        $this->db->bind(':created_at', $timestamp);

        $result = $this->db->execute();

        if ($result) {
            $messageId = (int) $this->db->lastInsertId();

            broadcast_realtime_message($bookingId, [
                'message_id' => $messageId,
                'sender_id'  => (int) $senderId,
                'message'    => $message,
                'created_at' => $timestamp,
                'delivered_at' => null,
                'read_at'      => null,
            ]);

            return $messageId;
        }

        return false;
    }

    public function getMessages($bookingId) {
        $this->db->query("SELECT * FROM messages WHERE booking_id = :booking_id ORDER BY created_at ASC");
        $this->db->bind(':booking_id', $bookingId);
        return $this->db->resultSet();
    }

    public function markDelivered($messageId) {
        $this->db->query("UPDATE messages SET delivered_at = NOW() WHERE id = :id AND delivered_at IS NULL");
        $this->db->bind(':id', $messageId);
        $this->db->execute();

        return $this->getMessageWithContext($messageId);
    }

    public function markRead($messageId) {
        $this->db->query("UPDATE messages SET delivered_at = COALESCE(delivered_at, NOW()), read_at = NOW() WHERE id = :id AND read_at IS NULL");
        $this->db->bind(':id', $messageId);
        $this->db->execute();

        return $this->getMessageWithContext($messageId);
    }

    public function getMessageWithContext($messageId) {
        $this->db->query("SELECT 
                m.*, 
                b.user_id AS booking_user_id, 
                gp.user_id AS guide_user_id
            FROM messages m
            JOIN bookings b ON m.booking_id = b.id
            JOIN guide_profiles gp ON b.guide_id = gp.id
            WHERE m.id = :id
            LIMIT 1");
        $this->db->bind(':id', $messageId);
        return $this->db->single();
    }
}