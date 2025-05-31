<?php
session_start();

$host = 'localhost';
$dbname = 'maljadb';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $enteredPassword = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        die("❌ Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

       
        if (password_verify($enteredPassword, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            
            switch ($user['role']) {
                case 'therapist':
                    header("Location: ../php/therapist-dashboard.php");
                    exit;
                case 'patient':
                    header("Location: ../php/homepagepatient.php");
                    exit;
                case 'admin':
                    header("Location: ../html/admindashboard.html");
                    exit;
                default:
                    echo "⚠️ Unknown role.";
                    break;
            }
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ User not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo "❌ Method Not Allowed";
}
?>