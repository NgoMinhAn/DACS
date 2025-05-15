<?php
class MessageModel {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function sendMessage($bookingId, $senderId, $message) {
        $this->db->query("INSERT INTO messages (booking_id, sender_id, message, created_at) VALUES (:booking_id, :sender_id, :message, NOW())");
        $this->db->bind(':booking_id', $bookingId);
        $this->db->bind(':sender_id', $senderId);
        $this->db->bind(':message', $message);
        return $this->db->execute();
    }

    public function getMessages($bookingId) {
        $this->db->query("SELECT * FROM messages WHERE booking_id = :booking_id ORDER BY created_at ASC");
        $this->db->bind(':booking_id', $bookingId);
        return $this->db->resultSet();
    }
}