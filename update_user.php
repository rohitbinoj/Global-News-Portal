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

$user_id = $_SESSION['user_id'];
$check_sql = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ssi", $username, $email, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "<script>alert('Username or email already exists for another user.'); window.history.back();</script>";
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET name=?, age=?, username=?, email=?, password=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisssi", $name, $age, $username, $email, $hashed_password, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Information updated successfully! Redirecting...'); window.location.href='newindex.html';</script>";
} else {
    echo "Error updating information: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
