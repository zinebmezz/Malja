<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    die("Unauthorized.");
}

$booking_id = $_POST['booking_id'] ?? null;

if (!$booking_id) {
    die("Invalid booking ID.");
}


$stmt = $pdo->prepare("UPDATE booking_requests SET status = 'accepted' WHERE id = ?");
$stmt->execute([$booking_id]);

header("Location: therapistdashboard.php");
exit;