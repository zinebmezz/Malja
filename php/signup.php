<?php

$host = 'localhost';
$dbname = 'maljadb';
$username = 'root';
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    
    if ($password !== $confirm) {
        die("⚠️ Passwords do not match.");
    }

    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        die("⚠️ This email is already registered.");
    }

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $user_id = uniqid('', true); 

   
    $insertUser = $pdo->prepare("INSERT INTO users (id, full_name, email, password, role) VALUES (?, ?, ?, ?, 'patient')");
$insertUser->execute([$user_id, $full_name, $email, $hashedPassword]);

    
    $insertPatient = $pdo->prepare("INSERT INTO patients (user_id, age, gender) VALUES (?, ?, ?)");
    $insertPatient->execute([$user_id, $age, $gender]);

   
    echo "✅ Signup successful! You can now log in.";
} else {
    
    http_response_code(405);
    echo "❌ Method Not Allowed";
}
?>