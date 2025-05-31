<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  exit('Not logged in');
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'] ?? null;

if (!$receiver_id) {
  http_response_code(400);
  exit('Missing receiver_id');
}

$stmt = $pdo->prepare("
  SELECT sender_id, message, sent_at 
  FROM chat_messages 
  WHERE (sender_id = ? AND receiver_id = ?) 
     OR (sender_id = ? AND receiver_id = ?)
  ORDER BY sent_at ASC
");
$stmt->execute([$sender_id, $receiver_id, $receiver_id, $sender_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach ($messages as &$msg) {
  $msg['is_sent_by_me'] = $msg['sender_id'] === $sender_id;
}

header('Content-Type: application/json');
echo json_encode($messages);