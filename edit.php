<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
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
$sql = "SELECT name, age, username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Edit Page</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #f4f4f4, #3498db);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    animation: gradientAnimation 15s ease infinite;
}
@keyframes gradientAnimation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.edit-container {
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 500px;
}
.edit-container h2 {
    margin-bottom: 20px;
    text-align: center;
}
.edit-container input {
    width: 100%;
    padding: 15px;
    margin: 15px 0;
    border: 2px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 16px;
    transition: border-color 0.3s ease;
}
.edit-container input:focus {
    border-color: #3498db;
    outline: none;
}
.edit-container input[type="submit"] {
    background-color: #2980b9;
    color: white;
    border: none;
    cursor: pointer;
}
.edit-container input[type="submit"]:hover {
    background-color: #2573a6;
}
</style>
</head>
<body>
<div class="edit-container">
    <h2>Edit Information</h2>
    <form action="update_user.php" method="POST" onsubmit="return validateForm()">
        <input type="text" id="name" name="name" placeholder="Name" required value="<?php echo htmlspecialchars($user['name']); ?>" />
        <input type="number" id="age" name="age" placeholder="Age" required value="<?php echo htmlspecialchars($user['age']); ?>" />
        <input type="text" id="username" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($user['username']); ?>" />
        <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($user['email']); ?>" />
        <input type="password" id="password" name="password" placeholder="Password" required />
        <input type="submit" value="Save Changes" />
        <a href="settings.html" class="back-button" style="display: block; text-align: center; margin-top: 20px; color: #3498db; text-decoration: none; font-size: 16px;">Back to Settings</a>
    </form>
</div>

<script>
function validateForm() {
    const name = document.getElementById('name').value;
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const namePattern = /^[A-Za-z\s]+$/;
    const usernamePattern = /^[a-zA-Z0-9._]{3,16}$/;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordPattern = /^.{6,}$/;

    if (!namePattern.test(name)) {
        alert('Name should only contain letters and optional spaces, no numbers or special characters.');
        return false;
    }

    const age = document.getElementById('age').value;
    if (age <= 0) {
        alert('Please enter a valid age.');
        return false;
    }

    if (!usernamePattern.test(username)) {
        alert('Username should be alphanumeric, can include underscores or dots, and should be between 3 to 16 characters.');
        return false;
    }

    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    if (!passwordPattern.test(password)) {
        alert('Password should be at least 6 characters long.');
        return false;
    }

    return true;
}
</script>
</body>
</html>