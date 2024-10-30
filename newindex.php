<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global News Portal</title>
    <link rel="stylesheet" href="newstyles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #cfe0f3;
    }
    </style>
</head>
<body>
    <header>
        <h1>Global News Portal</h1>
        <nav>
            <div class="main_nav">
                <a href="#">
                    <img src="Matrix News.jpg" alt="Global News Hub Logo" style="margin-right: -80px; border-radius: 50%; width: 60px; height: 60px;">
                </a>
                <div id="categories">
                    <button class="category-btn" data-category="preferences" aria-label="My Preferences" style="background: linear-gradient(to right, #287597, #1f89a1,#287597); color: #f0f8ff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">My Preferences</button>
                    <button class="category-btn" data-category="general" aria-label="General News">General</button>
                    <button class="category-btn" data-category="business" aria-label="Business News">Business</button>
                    <button class="category-btn" data-category="technology" aria-label="Technology News">Technology</button>
                    <button class="category-btn" data-category="sports" aria-label="Sports News">Sports</button>
                    <button class="category-btn" data-category="entertainment" aria-label="Entertainment News">Entertainment</button>
                </div>
                <div class="searchbar"> 
                    <input type="text" class="search" placeholder="Search news...">
                    <button class="searchbutton" aria-label="Search">Search</button>
                </div>
                <div id="user-info">
                    <div class="user-dropdown">
                        <img src="user.png" alt="User Icon" class="user-icon" aria-haspopup="true" aria-expanded="false" style="width: 50px; height: 50px; border-radius: 50%; cursor: pointer;">
                        <div class="dropdown-content" aria-label="User Menu">
                            <span id="username"></span>
                            <a href="settings.html" aria-label="Settings" style="display: block; text-align: center;">Settings</a>
                            <a href="search_history.php" aria-label="History" style="display: block; text-align: center;">Search History</a>
                            <button id="logout-btn" aria-label="Logout" style="display: block; text-align: center; color: red; text-decoration: none;" onclick="logout()">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div id="news-container"></div>
    </main>
    <script src="newscript.js"></script>
</body>
</html>