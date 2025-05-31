<?php
require_once 'db.php';
session_start();

$amount = $_GET['amount'];
$method = $_GET['method'];
$patient_id = $_GET['patient_id'];
$therapist_id = $_GET['therapist_id'];
$status = 'completed';

$stmt = $pdo->prepare("INSERT INTO payments (patient_id, therapist_id, amount, payment_method, status)
                       VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$patient_id, $therapist_id, $amount, $method, $status]);

echo "
  <html>
  <head><title>Payment Success</title></head>
  <body style='text-align:center; padding: 50px; font-family: sans-serif;'>
    <h2 style='color: green;'>Payment Completed</h2>
    <p>You have paid <strong>{$amount} DZD</strong> via <strong>" . ucfirst($method) . "</strong>.</p>
    <a href='dashboard.php'>Return to Dashboard</a>
  </body>
  </html>
";