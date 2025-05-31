<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Dashboard</title>
  <style>
   body {
      font-family: Arial, sans-serif;
      background: rgb(250, 252, 251);
      margin: 0;
      padding: 20px;
    }
    header {
      background: #eddddd;
      color: rgb(27, 24, 24);
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
      background: #eddddd;
      color: rgb(35, 31, 31);
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #edcfcf;
    }
    .message {
      background: #f4f4f4;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    input, select, textarea {
      width: 100%;
      padding: 0.5rem;
      margin-top: 0.5rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
  </style>
</head>
<body>
<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
  die("Unauthorized access.");
}

$patient_id = $_SESSION['user_id'];
$full_name = htmlspecialchars($_SESSION['full_name']);

$stmt = $pdo->prepare("SELECT br.*, u.full_name AS therapist_name
                        FROM booking_requests br
                        JOIN users u ON br.therapist_id = u.id
                        WHERE br.patient_id = ?
                        ORDER BY br.requested_time DESC");
$stmt->execute([$patient_id]);
$bookings = $stmt->fetchAll();
$stmt = $pdo->prepare("
  SELECT br.requested_time, br.status, u.full_name AS therapist_name
  FROM booking_requests br
  JOIN therapists t ON br.therapist_id = t.user_id
  JOIN users u ON u.id = t.user_id
  WHERE br.patient_id = ? AND br.status = 'accepted' AND br.requested_time >= NOW()
  ORDER BY br.requested_time ASC
");
$stmt->execute([$patient_id]);
$upcoming_sessions = $stmt->fetchAll();
?>

<header>
  <h1 style="color: #000;">Welcome, <?= $full_name ?></h1>
  <form action="logout.php" method="POST">
    <button type="submit">Logout</button>
  </form>
</header>
<br>
<br>
<br>
  <section>
  <h2>Upcoming Sessions</h2>
  <table>
    <tr><th>Therapist</th><th>Date</th><th>Time</th><th>Link</th></tr>
    <?php if (count($upcoming_sessions) > 0): ?>
      <?php foreach ($upcoming_sessions as $session): ?>
        <?php 
          $datetime = new DateTime($session['requested_time']);
          $date = $datetime->format('Y-m-d');
          $time = $datetime->format('H:i');
        ?>
        <tr>
          <td><?= htmlspecialchars($session['therapist_name']) ?></td>
          <td><?= $date ?></td>
          <td><?= $time ?></td>
          <td><a href="#" style="text-decoration: none; color: rgb(25, 83, 107); font-weight: bolder;">Join</a></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="4">No upcoming sessions yet.</td></tr>
    <?php endif; ?>
  </table>
</section>
<br>
<section>
  <h2>My Booking Requests</h2>
  <table>
    <tr>
      <th>Therapist</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
    </tr>
    <?php foreach ($bookings as $booking): 
      $datetime = new DateTime($booking['requested_time']);
      $date = $datetime->format('Y-m-d');
      $time = $datetime->format('H:i');
      $status = $booking['status'];
    ?>
    <tr>
      <td><?= htmlspecialchars($booking['therapist_name']) ?></td>
      <td><?= $date ?></td>
      <td><?= $time ?></td>
      <td class="status-<?= $status ?>"><?= ucfirst($status) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</section>
  <br>
  <section>
    <h2>Session History</h2>
    <table>
      <tr><th>Therapist</th><th>Date</th><th>Feedback</th></tr>
      <tr><td>Karim Saadi</td><td>2024-05-01</td><td>Great session, very helpful.</td></tr>
    </table>
  </section>
<br>
  <section>
    <h2>Messages</h2>
    <div class="message"><strong>Dr. Leila:</strong> Don’t forget your homework exercise 😊</div>
    <div class="message"><strong>Support:</strong> Your session link was updated</div>
  </section>
  <br>
  <section>
    <h2>Edit Profile</h2>
    <div id="profile-display">
      <img id="profile-pic" src="../image/320x400-3.jpeg" alt="Profile Picture" width="100" height="100" style="border-radius: 50%; margin-bottom: 10px;">
      <p><strong>Age:</strong> <span id="display-age">25</span></p>
      <p><strong>Gender:</strong> <span id="display-gender">Female</span></p>
      <p><strong>Therapy Preferences:</strong> <span id="display-preferences">Remote sessions, Cognitive therapy</span></p>
      <button onclick="showEditForm()">Update Profile</button>
    </div>
    </section>
    <br>
    <section>
        <h2>Upload Your Medical File</h2>
        <form>
          <label for="patient-file">Upload PDF or DOC File</label>
          <br>
          <input style="width: 400px;" type="file" id="patient-file" accept=".pdf, .doc, .docx">
          <button type="submit" style="margin-left: 20px;">Upload</button>
        </form>
        <br>
        <p><strong>Latest Uploaded File:</strong> <a href="#"style="text-decoration: none; color: rgb(25, 83, 107); font-weight: bolder;">View My File</a></p>
      </section>
<section>
    <form id="edit-profile-form" style="display: none;" onsubmit="saveProfile(event)">
      <label>Change Profile Picture</label>
      <input type="file" id="profile-pic-input" accept="image/*">

      <label>Therapy Preferences</label>
      <textarea id="input-preferences"></textarea>

      <button type="submit">Save</button>
      <button type="button" onclick="cancelEdit()">Cancel</button>
    </form>
  </section>

  <script>
    function showEditForm() {
      document.getElementById('input-preferences').value = document.getElementById('display-preferences').innerText;
      document.getElementById('profile-display').style.display = 'none';
      document.getElementById('edit-profile-form').style.display = 'block';
    }

    function saveProfile(event) {
      event.preventDefault();
      document.getElementById('display-preferences').innerText = document.getElementById('input-preferences').value;
      const picInput = document.getElementById('profile-pic-input');
      if (picInput.files && picInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById('profile-pic').src = e.target.result;
        };
        reader.readAsDataURL(picInput.files[0]);
      }
      document.getElementById('edit-profile-form').style.display = 'none';
      document.getElementById('profile-display').style.display = 'block';
    }

    function cancelEdit() {
      document.getElementById('edit-profile-form').style.display = 'none';
      document.getElementById('profile-display').style.display = 'block';
    }
 </script>
    <script>
    const fileInput = document.getElementById('patient-file');
    const fileLink = document.querySelector('a');
  
    fileInput.addEventListener('change', () => {
      if (fileInput.files.length > 0) {
        fileLink.innerText = fileInput.files[0].name;
      }
    });
  </script>
</body>
</html>


</body>
</html>