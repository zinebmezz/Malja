<?php
$host = 'localhost';
$dbname = 'maljadb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ DB connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $phone = $_POST['phone_number'];
    $wilaya = $_POST['wilaya'];
   $license_number = $_POST['license_number'];

    if ($password !== $confirm) {
        die("❌ Passwords do not match.");
    }

    $checkEmail = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->execute([$email]);
    if ($checkEmail->rowCount() > 0) {
        die("❌ Email already exists.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $user_id = uniqid('', true);

    $cvFileName = null;
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $cvTmpPath = $_FILES['cv_file']['tmp_name'];
        $cvName = basename($_FILES['cv_file']['name']);
        $cvFileName = $uploadDir . uniqid() . "_" . $cvName;
        if (!move_uploaded_file($cvTmpPath, $cvFileName)) {
    die("❌ Failed to save the uploaded CV.");
}
        move_uploaded_file($cvTmpPath, $cvFileName);
    }

    $stmt = $pdo->prepare("INSERT INTO users (id, full_name, email, password, role) VALUES (?, ?, ?, ?, 'therapist')");
    $stmt->execute([$user_id, $full_name, $email, $hashedPassword]);

    $stmt2 = $pdo->prepare("INSERT INTO therapists (user_id, license_number, is_verified) VALUES (?, ?, 0)");
    $stmt2->execute([$user_id, $license_number]);

    $stmt3 = $pdo->prepare("INSERT INTO therapist_profile (user_id, wilaya, phone_number, cv_file) VALUES (?, ?, ?, ?)");
    $stmt3->execute([$user_id, $wilaya, $phone, $cvFileName]);

    echo "✅ Therapist registered successfully.";
} else {
    http_response_code(405);
    echo "❌ Method not allowed";
}
?>