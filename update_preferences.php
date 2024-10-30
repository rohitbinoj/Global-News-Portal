<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to update your preferences.'); window.location.href='login.html';</script>";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "globalnewshub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$preferences = isset($_POST['preferences']) ? $_POST['preferences'] : [];
$preferences_string = implode(',', $preferences);

$sql = "UPDATE users SET preferences=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $preferences_string, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Preferences updated successfully!'); window.location.href='newindex.html';</script>";
} else {
    echo "Error updating preferences: " . $conn->error;
}

$stmt->close();
$conn->close();
?>