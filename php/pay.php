<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  die("Unauthorized");
}

$patient_id = $_SESSION['user_id'];
$therapist_id = $_GET['therapist_id'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout - Malja'</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8f4f4;
      text-align: center;
      padding: 60px;
    }
    .box {
      background: white;
      padding: 40px;
      display: inline-block;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    h2 {
      margin-bottom: 20px;
    }
    .method {
      border: 1px solid #ccc;
      padding: 20px;
      margin: 10px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: left;
    }
    .method:hover {
      background: #f2e6e6;
    }
    .method img {
      height: 30px;
      vertical-align: middle;
      margin-right: 10px;
    }
    .submit-btn {
      padding: 10px 30px;
      background: #d4baba;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="box">
  <h2>Choose a Payment Method</h2>

  <form method="POST" action="redirect_paypal.php">
    <input type="hidden" name="patient_id" value="<?= htmlspecialchars($patient_id) ?>">
    <input type="hidden" name="therapist_id" value="<?= htmlspecialchars($therapist_id) ?>">
    <input type="hidden" name="amount" value="2000">

    <div class="method">
      <label>
        <input type="radio" name="payment_method" value="paypal" required />
        <img src="../image/paypal.png" alt="PayPal"> Pay with PayPal
      </label>
    </div>

    <div class="method">
      <label>
        <input type="radio" name="payment_method" value="credit_card" />
        <img src="../image/credit-card.png" alt="Credit Card"> Pay with Credit Card
      </label>
    </div>

    <div class="method">
      <label>
        <input type="radio" name="payment_method" value="bank_transfer" />
        <img src="../image/transaction.png" alt="Bank Transfer"> Pay with Bank Transfer
      </label>
    </div>

    <br><br>
    <a style="text-decoration: none; color: black;" href="paypal-mock.html" type="submit" class="submit-btn">Confirm Payment</a>
  </form>
</div>

</body>
</html>