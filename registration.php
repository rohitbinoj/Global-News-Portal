<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "globalnewshub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$age = $_POST['age'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$preferences = isset($_POST['preferences']) ? implode(',', $_POST['preferences']) : '';

$stmt = $conn->prepare("SELECT * FROM users WHERE email=? OR username=?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Email or Username already exists.'); window.location.href='register.html';</script>";
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, age, username, email, password, preferences, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sissss", $name, $age, $username, $email, $password, $preferences);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Redirecting to login...'); window.location.href='login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>