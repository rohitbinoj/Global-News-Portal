<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "globalnewshub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed']));
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT preferences FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['preferences' => $row['preferences']]);
    } else {
        echo json_encode(['preferences' => '']);
    }
    $stmt->close();
} else {
    echo json_encode(['preferences' => '']);
}

$conn->close();
?>