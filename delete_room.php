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
    $room_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "DELETE FROM rooms WHERE id = '$room_id'";
    if (mysqli_query($conn, $query)) {
        header("Location: manageRooms.php?success=Room assignment deleted successfully!");
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>