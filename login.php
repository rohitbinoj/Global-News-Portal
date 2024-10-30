<?php
session_start();  
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "globalnewshub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username_email = $_POST['username_email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  if (password_verify($password, $row['password'])) {
    $_SESSION['user_id'] = $row['id'];  
    $_SESSION['username'] = $row['username'];  
    $_SESSION['email'] = $row['email'];  

    echo "<script> window.location.href='newindex.html';</script>";
  } else {
    echo "<script>alert('Invalid password.'); window.location.href='login.html';</script>";
  }
} else {
  echo "<script>alert('No user found with that username/email.'); window.location.href='login.html';</script>";
}

$conn->close();
?>