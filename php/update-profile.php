<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
  die("Unauthorized");
}

$host = 'localhost';
$dbname = 'maljadb';
$username = 'root';
$password = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("DB Error: " . $e->getMessage());
}

$user_id = $_SESSION['user_id'];
$specialization = $_POST['specialization'] ?? null;
$bio = $_POST['bio'] ?? null;


$profile_picture_path = null;
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $tmpName = $_FILES['profile_pic']['tmp_name'];
    $filename = uniqid() . "_" . basename($_FILES['profile_pic']['name']);
    $destination = $uploadDir . $filename;
    if (move_uploaded_file($tmpName, $destination)) {
        $profile_picture_path = $destination;
    }
}


$sql = "UPDATE therapist_profile SET specialization = ?, bio = ?";
$params = [$specialization, $bio];

if ($profile_picture_path) {
    $sql .= ", profile_picture = ?";
    $params[] = $profile_picture_path;
}
$sql .= " WHERE user_id = ?";
$params[] = $user_id;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

header("Location: ../html/therapist-dashboard.php");
exit;
?>