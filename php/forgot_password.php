<?php
$host = 'localhost';
$dbname = 'maljadb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
           
            echo "✅ A password reset link will be sent to your email (simulated).";
        } else {
            echo "❌ No account found with that email.";
        }
    } else {
        http_response_code(405);
        echo "Method not allowed.";
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}