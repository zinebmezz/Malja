<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../php/db.php'; 
if (!isset($_SESSION['user_id'])) {
  die("Unauthorized access");
}

$currentUserId = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT DISTINCT u.id, u.full_name
  FROM users u
  JOIN chat_messages m ON (u.id = m.sender_id OR u.id = m.receiver_id)
  WHERE u.id != ? AND (m.sender_id = ? OR m.receiver_id = ?)");
$stmt->execute([$currentUserId, $currentUserId, $currentUserId]);
$contacts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Messages | Maljā</title>
  <style>
   * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background-color: #f9f6f4;
    }

    .messaging-container {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 25%;
      background-color: #fff;
      border-right: 1px solid #ddd;
      padding: 20px;
    }

    .sidebar h2 {
      font-size: 22px;
      color: #4f3e3e;
      margin-bottom: 20px;
    }

    .search-box {
      margin-bottom: 20px;
    }

    .search-box input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: #fdfafa;
    }

    .conversation {
      background-color: #f7eeee;
      padding: 12px;
      border-radius: 10px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .conversation:hover {
      background-color: #e8d6d6;
    }

    .chat-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
      background-color: #fff;
    }

    .chat-header {
      padding: 16px;
      background-color: #eddddd;
      border-bottom: 1px solid #ccc;
      font-weight: bold;
      font-size: 18px;
    }

    .chat-messages {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 10px;
      background-color: #fefefe;
    }

    .message {
      max-width: 60%;
      padding: 12px 16px;
      border-radius: 20px;
      font-size: 15px;
    }

    .message.sent {
      background-color: #d9f1ec;
      align-self: flex-end;
    }

    .message.received {
      background-color: #f1eaea;
      align-self: flex-start;
    }

    .chat-input {
      display: flex;
      border-top: 1px solid #ccc;
      padding: 12px;
    }

    .chat-input input {
      flex: 1;
      padding: 10px 15px;
      border: 1px solid #ccc;
      border-radius: 20px;
      outline: none;
    }

    .chat-input button {
      padding: 10px 20px;
      background-color: #eddddd;
      border: none;
      margin-left: 10px;
      border-radius: 20px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .chat-input button:hover {
      background-color: #d6bcbc;
    }

    .no-messages {
      padding: 40px;
      text-align: center;
      font-size: 18px;
      color: #888;
    }
  </style>
</head>
<body>
<div class="messaging-container">
 
  <div class="sidebar">
    <h2>Your Messages</h2>
    <div class="search-box">
      <input type="text" placeholder="Search therapist..." />
    </div>
    <?php foreach ($contacts as $c): ?>
      <div class="conversation" onclick="openChat('<?= htmlspecialchars($c['full_name']) ?>', <?= $c['id'] ?>)">
        <?= htmlspecialchars($c['full_name']) ?>
      </div>
    <?php endforeach; ?>
  </div>

  
  <div class="chat-panel">
    <div class="chat-header" id="chatHeader">Select a conversation</div>

    <div class="chat-messages" id="chatMessages">
      <div class="no-messages">No conversation selected.</div>
    </div>

    <div class="chat-input" id="chatInput" style="display: none;">
      <input type="text" placeholder="Type your message...">
      <button>Send</button>
    </div>
  </div>
</div>

<script>
  const loggedInUserId = <?= json_encode($currentUserId) ?>;
  let currentReceiverId = null;

  function openChat(name, receiverId) {
    currentReceiverId = receiverId;
    document.getElementById("chatHeader").textContent = name;
    document.getElementById("chatInput").style.display = "flex";
    fetchMessages();
    if (window.chatInterval) clearInterval(window.chatInterval);
    window.chatInterval = setInterval(fetchMessages, 5000);
  }

  function fetchMessages() {
    fetch(`../php/load_messages.php?receiver_id=${currentReceiverId}`)
      .then(res => res.json())
      .then(data => {
        const messagesContainer = document.getElementById("chatMessages");
        messagesContainer.innerHTML = "";
        data.forEach(msg => {
          const div = document.createElement("div");
          div.className = "message " + (msg.sender_id == loggedInUserId ? "sent" : "received");
          const senderLabel = msg.sender_id == loggedInUserId ? "You" : "Them";
div.innerHTML = `
  <div><strong>${senderLabel}</strong></div>
  <div>${msg.message}</div>
  <div style="font-size: 0.75rem; color: gray; margin-top: 4px;">${new Date(msg.sent_at).toLocaleString()}</div>
`;
          messagesContainer.appendChild(div);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
      });
  }

  document.querySelector(".chat-input button").addEventListener("click", () => {
    const input = document.querySelector(".chat-input input");
    const message = input.value.trim();
    if (!message || !currentReceiverId) return;

    fetch("../php/send_message.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `receiver_id=${currentReceiverId}&message=${encodeURIComponent(message)}`
    }).then(() => {
      input.value = "";
      fetchMessages();
    });
  });
</script>
</body>
</html>