<?php
session_start();
$amount = $_POST['amount'];
$patient = $_POST['patient_id'];
$therapist = $_POST['therapist_id'];
$method = $_POST['payment_method'];

if ($method === 'paypal') {
  echo "
    <html>
    <head>
      <meta http-equiv='refresh' content='3;url=process_payment.php?amount=$amount&patient_id=$patient&therapist_id=$therapist&method=paypal'>
      <style>
        body { text-align: center; padding: 50px; font-family: sans-serif; background: #fafafa; }
        .loader { margin-top: 20px; font-size: 1.1em; color: #777; }
      </style>
    </head>
    <body>
      <h2>Redirecting to PayPal...</h2>
      <div class='loader'>Please wait<span id='dots'>.</span></div>
      <script>
        setInterval(() => {
          document.getElementById('dots').textContent += '.';
        }, 500);
      </script>
    </body>
    </html>
  ";
  exit;
}

header("Location: process_payment.php");
exit;