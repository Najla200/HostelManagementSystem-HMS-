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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $hostel_name = mysqli_real_escape_string($conn, $_POST['hostel_name']);
    $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);

    // Check if student_id already exists (due to UNIQUE constraint)
    $check_student_id = "SELECT id FROM rooms WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $check_student_id);
    if (mysqli_num_rows($result) > 0) {
        header("Location: manageRooms.php?error=Student ID already assigned to a room."); 
        exit;
    }

    // Insert room assignment
    $query = "INSERT INTO rooms (student_id, name, room_number, hostel_name) 
              VALUES ('$student_id', '$name', '$room_number', '$hostel_name')";
    if (mysqli_query($conn, $query)) {
        header("Location: manageRooms.php?success=Room assigned successfully!"); 
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>