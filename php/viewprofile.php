<?php
session_start();
require_once '../php/db.php'; 


if (!isset($_GET['id'])) {
  die("⚠️ No therapist selected.");
}

$therapist_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);


$stmt = $pdo->prepare("
  SELECT tp.*, u.full_name 
  FROM therapist_profile tp 
  JOIN users u ON tp.user_id = u.id 
  WHERE tp.user_id = ?
");
$stmt->execute([$therapist_id]);
$profile = $stmt->fetch();

if (!$profile) {
  die("❌ Therapist not found.");
}


$name = htmlspecialchars($profile['full_name']);
$specialization = htmlspecialchars($profile['specialization']);
$experience = htmlspecialchars($profile['experience']);
$availability = htmlspecialchars($profile['availability']);
$certifications = htmlspecialchars($profile['certifications']);
$bio = htmlspecialchars($profile['bio']);
$languages = htmlspecialchars($profile['languages']);
$price = number_format($profile['price'], 2);
$picture = '../uploads/' . $profile['profile_picture'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Therapist Profile</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #faf7f4;
      color: #2d2d2d;
      margin: 0;
      padding: 0;
    }

    .profile-container {
      max-width: 900px;
      margin: 50px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      padding: 30px;
    }

    .profile-header {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    .profile-header img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #d9c1aa;
    }

    .profile-header h2 {
      font-size: 28px;
      margin: 0;
      color: #533c2c;
    }

    .profile-details {
      margin-top: 30px;
      line-height: 1.8;
    }

    .profile-details h3 {
      color: #7a5e4a;
      margin-bottom: 10px;
    }

    .profile-details p {
      margin: 5px 0;
    }

    .rating {
      color: #f5a623;
      font-size: 20px;
      margin-top: 10px;
    }

    .book-btn {
      margin-top: 30px;
      padding: 12px 24px;
      background-color: #eddddd;
      border: none;
      border-radius: 8px;
      color: #2d2d2d;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .book-btn:hover {
      background-color: #e9c4c4;
    }

    .reviews {
      margin-top: 50px;
    }

    .review {
      background: #f6f1ed;
      border-left: 2px solid #babab9;
      padding: 15px 20px;
      margin-bottom: 15px;
      border-radius: 5px;
    }

    .review strong {
      color: #533c2c;
    }
  </style>
</head>
<body>

  <div class="profile-container">
    <div class="profile-header">
      <img src="<?= $picture ?>" alt="<?= $name ?>" />
      <div>
        <h2><?= $name ?></h2>
        <p><?= $specialization ?></p>
        <div class="rating">★★★★☆ (4.8)</div>
      </div>
    </div>

    <div class="profile-details">
      <h3>Bio</h3>
      <p><strong><?= $bio ?></strong></p>

      <h3>Experience</h3>
      <p><strong><?= $experience ?> Years of Clinical Experience</strong></p>
      
      <h3>Certifications</h3>
<p style="font-weight: bold;"><?= $certifications ?></p>
<h3>Availability</h3>
<p style="font-weight: bold;"><?= $availability ?></p>

      <h3>Languages</h3>
      <p><strong><?= $languages ?></strong></p>

      <h3>Session Price</h3>
      <p><strong><?= $price ?> DZD</strong></p>

     <a href="book.php?therapist_id=<?= $therapist_id ?>" class="book-btn" style="text-decoration: none;">Book a Session</a>
    </div>

    <div class="reviews">
      <h3>Patient Reviews</h3>
      <div class="review">
        <strong>Lina:</strong> “<?= $name ?> helped me open up and finally get the help I needed.”
      </div>
      <div class="review">
        <strong>Amine:</strong> “She helped me through my darkest moments. I felt truly heard.”
      </div>
    </div>
  </div>

</body>
</html>