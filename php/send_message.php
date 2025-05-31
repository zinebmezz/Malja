<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo 'Not logged in.';
  exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');

if (!$receiver_id || $message === '') {
  http_response_code(400);
  echo 'Missing receiver or message.';
  exit;
}

$stmt = $pdo->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->execute([$sender_id, $receiver_id, $message]);

echo 'Message sent';