<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
  die("Unauthorized access.");
}

$therapist_id = $_SESSION['user_id']; 
$day = $_POST['day'] ?? null;
$start = $_POST['start_time'] ?? null;
$end = $_POST['end_time'] ?? null;

if (!$day || !$start || !$end) {
  die("Please fill all fields.");
}


$check = $pdo->prepare("SELECT id FROM availability WHERE therapist_id = ? AND available_day = ?");
$check->execute([$therapist_id, $day]);

if ($check->rowCount() > 0) {
  
  $update = $pdo->prepare("
    UPDATE availability SET start_time = ?, end_time = ? 
    WHERE therapist_id = ? AND available_day = ?
  ");
  $update->execute([$start, $end, $therapist_id, $day]);
} else {
  
  $insert = $pdo->prepare("
    INSERT INTO availability (therapist_id, available_day, start_time, end_time)
    VALUES (?, ?, ?, ?)
  ");
  $insert->execute([$therapist_id, $day, $start, $end]);
}

header("Location: therapist-dashboard.php?availability_saved=1");
exit;