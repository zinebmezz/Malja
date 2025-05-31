<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uploadDir = "../uploads/profile_pic/";
    $filename = time() . "_" . basename($_FILES["profile_pic"]["name"]);
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath)) {
        die("Error uploading profile picture.");
    }

   
    $specialization = implode(",", $_POST['specialization']);
    $certifications = $_POST['certifications'];
    $languages = $_POST['languages'];
    $bio = $_POST['bio'];
    $experience = $_POST['experience'];
    $session_type = implode(",", $_POST['session_type']);
    $price = $_POST['price'];
    $profile_picture_path = "profile_pic/" . $filename; 

    
    $stmt = $pdo->prepare("
  INSERT INTO therapist_profile 
    (user_id, specialization, experience, bio, certifications, languages, session_type, price, profile_picture, availability)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ON DUPLICATE KEY UPDATE 
    specialization = VALUES(specialization),
    experience = VALUES(experience),
    bio = VALUES(bio),
    certifications = VALUES(certifications),
    languages = VALUES(languages),
    session_type = VALUES(session_type),
    price = VALUES(price),
    profile_picture = VALUES(profile_picture),
    availability = VALUES(availability)
");
    $stmt->execute([
  $user_id,
  $specialization,
  $experience,
  $bio,
  $certifications,
  $languages,
  $session_type,
  $price,
  "profile_pic/" . $filename,
  $availability  
]);

    header("Location: ../php/therapist-dashboard.php");
    exit;
}
?>