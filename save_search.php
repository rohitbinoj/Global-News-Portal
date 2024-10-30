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

$search_term = $_POST['search_term'];
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Default to 1 if not logged in
$search_date = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO search_history (iduser, search_term, search_date) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $search_term, $search_date);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Search history saved"]);
} else {
    echo json_encode(["success" => false, "message" => "Error saving search history"]);
}

$stmt->close();
$conn->close();
?>