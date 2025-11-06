<?php
// booking.php

session_start();
require_once '../../config/database.php'; // Adjust path as needed

// Check if guide is logged in
if (!isset($_SESSION['guide_id'])) {
    header('Location: /login.php');
    exit();
}

// Get booking ID from query parameter
if (!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])) {
    echo "Invalid booking ID.";
    exit();
}

$booking_id = intval($_GET['booking_id']);
$guide_id = $_SESSION['guide_id'];

// Fetch booking details
$stmt = $pdo->prepare("
    SELECT b.*, u.name AS user_name, u.email AS user_email
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ? AND b.guide_id = ?
");
$stmt->execute([$booking_id, $guide_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    echo "Booking not found or you do not have permission to view this booking.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Detail</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Booking Detail</h2>
    <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking['id']) ?></p>
    <p><strong>Customer Name:</strong> <?= htmlspecialchars($booking['user_name']) ?></p>
    <p><strong>Customer Email:</strong> <?= htmlspecialchars($booking['user_email']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($booking['date']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($booking['status']) ?></p>
    <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>