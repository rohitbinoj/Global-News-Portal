<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "globalnewshub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? null;

if (!$rating || !$comment) {
    echo "<script>alert('Please provide both rating and comment.'); window.location.href='feedback.html';</script>";
    exit();
}

$sql = "INSERT INTO feedback (user_id, rating, comment) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $rating, $comment);

if ($stmt->execute()) {
    echo "<script>
            alert('Thank you for your feedback!'); 
            window.location.href='newindex.html';
          </script>";
} else {
    echo "<script>
            alert('Error submitting feedback. Please try again.'); 
            window.location.href='feedback.html';
          </script>";
}

$stmt->close();
$conn->close();
?>