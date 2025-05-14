<?php
// Start session
session_start();

// Database credentials
$host = 'localhost';
$dbname = 'hostel'; 
$username = 'root';
$password_db = ''; 

// Create connection
$conn = mysqli_connect($host, $username, $password_db, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Fetch user from database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Verify user and password
    if ($user && password_verify($password, $user['password'])) {
        // Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin_dash.php");
        } else {
            header("Location: student_dashboard.php");
        }
        exit;
    } else {
        // Redirect back to login with error
        header("Location: login.php?error=Invalid email or password.");
        exit;
    }
}

mysqli_close($conn);
?>