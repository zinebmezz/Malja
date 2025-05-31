<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'db.php';

$patient_id = $_SESSION['user_id'] ?? null;
$therapist_id = $_POST['therapist_id'] ?? null;
$date = $_POST['date'] ?? null;
$time = $_POST['time'] ?? null;
$session_type = $_POST['session-type'] ?? null;
$message = $_POST['message'] ?? null;

if (!$patient_id || !$therapist_id || !$date || !$time || !$session_type) {
  die("Missing required fields.");
}

$requested_time = $date . ' ' . $time;

$stmt = $pdo->prepare("INSERT INTO booking_requests (patient_id, therapist_id, requested_time, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
$stmt->execute([$patient_id, $therapist_id, $requested_time]);



header("Location: homepagepatient.php?booked=1");
exit;