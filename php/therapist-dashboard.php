<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("
  SELECT 
    u.full_name,
    tp.specialization,
    tp.bio,
    tp.experience,
    tp.certifications,
    tp.profile_picture,
    t.license_number
  FROM users u
  JOIN therapists t ON u.id = t.user_id
  LEFT JOIN therapist_profile tp ON tp.user_id = u.id
  WHERE u.id = ?
");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

$full_name = $data['full_name'] ?? 'Therapist';
$specialization = $data['specialization'] ?? 'Not specified';
$bio = $data['bio'] ?? 'No bio provided.';
$experience = $data['experience'] ?? 'N/A';
$certifications = $data['certifications'] ?? 'None';
$license = $data['license_number'] ?? 'N/A';
$profile_picture = "../uploads/profile_pic" . ($data['profile_picture'] ?? 'default.png');

$bookingStmt = $pdo->prepare("
  SELECT br.id, br.requested_time, u.full_name AS patient_name
  FROM booking_requests br
  JOIN users u ON br.patient_id = u.id
  WHERE br.therapist_id = ? AND br.status = 'pending'
  ORDER BY br.requested_time ASC
");
$bookingStmt->execute([$user_id]);
$booking_requests = $bookingStmt->fetchAll();

$avail_stmt = $pdo->prepare("SELECT available_day, start_time, end_time FROM availability WHERE therapist_id = ?");
$avail_stmt->execute([$user_id]);
$availabilities = $avail_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Therapist Profile</title>
  <style>
      body {
      font-family: Arial, sans-serif;
      background:rgb(250, 252, 251);
      margin: 0;
      padding: 20px;
    }
    header {
      background: #eddddd;
      color: rgb(35, 31, 31);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 10px;
    }
    section {
      background: #ffffff;
      padding: 1rem 2rem;
      margin: 1.5rem 0;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 {
      margin-top: 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    th, td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    button {
      background:#eddddd;
      color: rgb(41, 36, 36);
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 5px;
      margin-right: 5px;
      cursor: pointer;
    }
    button:hover {
      background:#f5d5d5;
    }
    input, select, textarea {
      width: 100%;
      padding: 0.5rem;
      margin-top: 0.5rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .message {
      background: #f4f4f4;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .buttons{
  display: flex;
  gap: 20px;
    }
    .review {
  border: 1px solid #ddd;
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 8px;
  background-color: #f9f9f9;
}
.review p {
  margin: 0.5rem 0;
}
  
  </style>
</head>
<body>
 <header>
    <h1 style="color: #000;">Welcome, <?= htmlspecialchars($full_name) ?></h1>
    <a href="../html/complete.html" style="color: #000; font-size: 20px; font-weight: bold; text-decoration: none; padding: 10px 20px; border: 1px solid #000; border-radius: 5px;">Complete your profile</a>
    <button style="color: #000; font-size: 20px; font-weight: bold;">Logout</button>
  </header>
<br>
<br>
  
  <section>
    <h2>Upcoming Sessions</h2>
    <table>
      <tr><th>Patient</th><th>Date</th><th>Time</th><th>Link</th></tr>
      <tr><td>Sarah M.</td><td>2024-05-12</td><td>14:00</td><td><a href="#" style="text-decoration: none; color: rgb(25, 83, 107); font-weight: bolder;">Join</a></td></tr>
      <tr><td>Youssef A.</td><td>2024-05-13</td><td>10:00</td><td><a href="#" style="text-decoration: none; color: rgb(25, 83, 107);font-weight: bolder;">Join</a></td></tr>
      <tr><td>Rania F.</td><td>2024-05-13</td><td>18:00</td><td><a href="#" style="text-decoration: none; color: rgb(25, 83, 107); font-weight: bolder;">Join</a></td></tr>
    </table>
  </section>

<br>

  <section>
  <h2>Booking Requests</h2>
  <?php if (count($booking_requests) > 0): ?>
    <?php foreach ($booking_requests as $req): ?>
      <div>
        <p><strong><?= htmlspecialchars($req['patient_name']) ?></strong> — <?= date('Y-m-d \a\t H:i', strtotime($req['requested_time'])) ?></p>
        <div class="buttons">
          <form action="../php/accept.php" method="POST" style="display:inline;">
            <input type="hidden" name="booking_id" value="<?= $req['id'] ?>">
            <button type="submit" style="background-color: rgb(167, 208, 167); color: #000;">Accept</button>
          </form>
          <form action="../php/reject.php" method="POST" style="display:inline;">
            <input type="hidden" name="booking_id" value="<?= $req['id'] ?>">
            <button type="submit" style="background-color: rgb(230, 152, 152); color: #000;">Reject</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No pending booking requests.</p>
  <?php endif; ?>
</section>

 
<br>
 
  <section>
  <h2>Availability Settings</h2>
  <form method="POST" action="../php/save_availability.php" 
        style="display: flex; flex-direction: column; gap: 10px; padding-left: 500px;">

    <label for="day">Available Day</label>
    <select name="day" id="day" style="max-width: 215px;" required>
      <option value="">-- Select --</option>
      <option value="Sunday">Sunday</option>
      <option value="Monday">Monday</option>
      <option value="Tuesday">Tuesday</option>
      <option value="Wednesday">Wednesday</option>
      <option value="Thursday">Thursday</option>
      <option value="Friday">Sunday</option>
      <option value="Saturday">Sunday</option>
    </select>

    <label for="start_time">Start Time</label>
    <input type="time" name="start_time" id="start_time" 
           style="font-size: 14px; padding: 4px; max-width: 200px;" required>

    <label for="end_time">End Time</label>
    <input type="time" name="end_time" id="end_time" 
           style="font-size: 14px; padding: 4px; max-width: 200px;" required>

    <button type="submit" 
            style="margin-left: 70px; max-width: 80px; padding: 6px 10px; color: #000;">
      Save
    </button>
  </form>
</section>
<section>
  <h2>Your Availability</h2>
  <?php if (count($availabilities) > 0): ?>
    <ul>
      <?php foreach ($availabilities as $a): ?>
        <li><?= htmlspecialchars($a['available_day']) ?>: <?= $a['start_time'] ?> - <?= $a['end_time'] ?></li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>You haven't added availability yet.</p>
  <?php endif; ?>
</section>
  <br>
  <section>
    <h2>Patient Reviews</h2>
    <div class="overall-rating" style="font-size: 1.2rem; margin-bottom: 1rem;">
      ⭐ Overall Rating: <strong>4.7 / 5</strong> (based on 36 reviews)
    </div>
  
    <div class="review">
      <p><strong>Sarah M.</strong> — <em>Rated: 5 ★</em></p>
      <p>"Absolutely wonderful experience. I feel more in control of my anxiety."</p>
    </div>
  
    <div class="review">
      <p><strong>Youssef A.</strong> — <em>Rated: 4 ★</em></p>
      <p>"Very helpful session. Looking forward to the next one."</p>
    </div>
  
    <div class="review">
      <p><strong>Rania F.</strong> — <em>Rated: 5 ★</em></p>
      <p>"The therapist was kind, understanding, and truly insightful."</p>
    </div>
  </section>
<br>
  <section>
    <h2>Edit Profile</h2>

    <div id="profile-display">
  <img id="profile-pic" src="<?= $profile_picture ?>" alt="Profile Picture" width="100" height="100" style="border-radius: 50%; margin-bottom: 10px;">
<p><strong>Experience:</strong> <span id="display-experience"><?= htmlspecialchars($experience) ?> years</span></p>
<p><strong>Specialization:</strong> <span id="display-specialization"><?= htmlspecialchars($specialization) ?></span></p>
<p><strong>Bio:</strong> <span id="display-bio"><?= htmlspecialchars($bio) ?></span></p>
<p><strong>Certifications:</strong> <span id="display-certifications"><?= htmlspecialchars($certifications) ?>, License #<?= htmlspecialchars($license) ?></span></p>
  <button onclick="showEditForm()" style="color: #000;">Update Profile</button>
</div>
   <section>
    <form id="edit-profile-form" style="display: none;" onsubmit="return true;" method="POST" enctype="multipart/form-data" action="../php/update-profile.php">
  <label>Change Profile Picture</label>
  <input type="file" name="profile_pic" id="profile-pic-input" accept="image/*">

  <label>Specialization</label>
  <input type="text" name="specialization" id="input-specialization">

  <label>Bio</label>
  <textarea name="bio" id="input-bio"></textarea>

  <button type="submit">Save</button>
  <button type="button" onclick="cancelEdit()" style="color: #000;">Cancel</button>
</form>
  </section>
<br>
 
  <section>
    <h2>Messages</h2>
    <div class="message">
      <strong>Sarah:</strong> Thank you for today’s session!
    </div>
    <div class="message">
      <strong>Omar:</strong> Will tomorrow’s session be on the same link?
    </div>
    <div class="message">
      <strong>Rania:</strong> I’d like to reschedule if possible.
    </div>
    <a href="../php/message.php" style="color: #000; font-size: 14px; font-weight: bold; text-decoration: none; padding: 5px 10px; border: 1px solid #000; border-radius: 5px;" >view messages<a>
  </section>

  <script>
    function showEditForm() {
      
      document.getElementById('input-specialization').value = document.getElementById('display-specialization').innerText;
      document.getElementById('input-bio').value = document.getElementById('display-bio').innerText;
      
  
      document.getElementById('profile-display').style.display = 'none';
      document.getElementById('edit-profile-form').style.display = 'block';
    }
  
    function saveProfile(event) {
      event.preventDefault();
  
   
      document.getElementById('display-specialization').innerText = document.getElementById('input-specialization').value;
      document.getElementById('display-bio').innerText = document.getElementById('input-bio').value;
      
  
      
      const picInput = document.getElementById('profile-pic-input');
      if (picInput.files && picInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById('profile-pic').src = e.target.result;
        };
        reader.readAsDataURL(picInput.files[0]);
      }
  
     
      document.getElementById('profile-display').style.display = 'block';
      document.getElementById('edit-profile-form').style.display = 'none';
    }
  
    function cancelEdit() {
      document.getElementById('edit-profile-form').style.display = 'none';
      document.getElementById('profile-display').style.display = 'block';
    }
  </script>
</body>
</html>