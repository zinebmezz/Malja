<?php
$notifications = [
  "📩 New message from Dr. Leila",
  "📅 Session approved: May 24, 14:00",
  "✅ Your profile has been verified",
];

foreach ($notifications as $note) {
    echo "<p>" . htmlspecialchars($note) . "</p>";
}
?>