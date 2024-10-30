<?php
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to update your information.'); window.location.href='login.html';</script>";
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

$name = $_POST['name'] ?? null;
$age = $_POST['age'] ?? null;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (!$name || !$age || !$username || !$email || !$password) {
    echo "Error: Form data is missing.";
    exit();
}

echo "Form data received successfully.<br>";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$user_id = $_SESSION['user_id'];

echo "User ID from session: " . $user_id . "<br>";

$sql = "UPDATE users SET name='$name', age='$age', username='$username', email='$email', password='$hashed_password' WHERE id='$user_id'";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Information updated successfully! Redirecting...'); window.location.href='newindex.html';</script>";
} else {
  echo "Error updating information: " . $conn->error;
}

$conn->close();
?>