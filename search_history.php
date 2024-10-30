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

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

if (isset($_POST['clear_history'])) {
    $clear_stmt = $conn->prepare("DELETE FROM search_history WHERE iduser = ?");
    $clear_stmt->bind_param("i", $user_id);
    $clear_stmt->execute();
    $clear_stmt->close();
    header("Location: search_history.php?cleared=1");
    exit();
}

$stmt = $conn->prepare("SELECT search_term, search_date FROM search_history WHERE iduser = ? ORDER BY search_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search History - Global News Hub</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        .history-container {
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .history-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .history-item:last-child {
            border-bottom: none;
        }
        .search-term {
            font-weight: bold;
            color: #007bff;
            cursor: pointer;
            transition: color 0.3s;
        }
        .search-term:hover {
            color: #0056b3;
        }
        .search-date {
            color: #666;
            font-size: 0.9em;
        }
        .back-button, .clear-button {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .back-button {
            background-color: #007bff;
            color: white;
            margin-right: 10px;
        }
        .clear-button {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        .clear-button:hover {
            background-color: #c82333;
        }
        h1 {
            color: #333;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .button-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php if (isset($_GET['cleared'])): ?>
    <div class="alert">Search history has been cleared successfully!</div>
    <?php endif; ?>

    <div class="button-container">
        <a href="newindex.html" class="back-button">Back to Home</a>
        <?php if ($result->num_rows > 0): ?>
        <form method="POST" style="display: inline;" onsubmit="return confirmClear()">
            <button type="submit" name="clear_history" class="clear-button">Clear History</button>
        </form>
        <?php endif; ?>
    </div>

    <h1>Your Search History</h1>
    <div class="history-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="history-item">';
                echo '<span class="search-term" onclick="searchAgain(\'' . htmlspecialchars($row['search_term'], ENT_QUOTES) . '\')">' . 
                     htmlspecialchars($row['search_term']) . '</span>';
                echo '<span class="search-date">' . date('M d, Y H:i', strtotime($row['search_date'])) . '</span>';
                echo '</div>';
            }
        } else {
            echo '<p>No search history found.</p>';
        }
        ?>
    </div>

    <script>
        function searchAgain(searchTerm) {
            window.location.href = `index.html?search=${encodeURIComponent(searchTerm)}`;
        }

        function confirmClear() {
            return confirm('Are you sure you want to clear your entire search history? This action cannot be undone.');
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>