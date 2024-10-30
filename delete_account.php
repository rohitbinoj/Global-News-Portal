<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to delete your account.'); window.location.href='login.html';</script>";
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "globalnewshub";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM search_history WHERE iduser = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM feedback WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE users SET preferences = NULL WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $conn->commit();
            
            session_unset();
            session_destroy();
            
            // Clear session cookie
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-3600, '/');
            }
            
            echo "<script>
                    alert('Your account has been successfully deleted. Redirecting to homepage...');
                    window.location.href='index.html';
                  </script>";
        } else {
            throw new Exception("Error deleting user account");
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>
                alert('Error during account deletion: " . $e->getMessage() . "');
                window.location.href='settings.html';
              </script>";
    }

} catch (Exception $e) {
    echo "<script>
            alert('Database error: " . $e->getMessage() . "');
            window.location.href='settings.html';
          </script>";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>