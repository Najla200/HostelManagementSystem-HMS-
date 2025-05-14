<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$dbname = 'hostel';
$username = 'root';
$password_db = '';

$conn = mysqli_connect($host, $username, $password_db, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $announcement_id = mysqli_real_escape_string($conn, $_GET['id']);
    $delete_query = "DELETE FROM announcements WHERE id = '$announcement_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: announcements.php?success=Announcement deleted successfully!");
        exit;
    } else {
        header("Location: announcements.php?error=Error deleting announcement: " . mysqli_error($conn));
        exit;
    }
} else {
    header("Location: announcements.php?error=No announcement ID provided.");
    exit;
}

mysqli_close($conn);
?>