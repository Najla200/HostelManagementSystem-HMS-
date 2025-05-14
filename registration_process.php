<?php
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
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }
    if (strlen($password) < 6) {
        die("Password must be at least 6 characters long.");
    }
    if (!in_array($role, ['student', 'admin'])) {
        die("Invalid role selected.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_query = "SELECT COUNT(*) FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_query);
    $count = mysqli_fetch_array($result)[0];
    if ($count > 0) {
        die("Email already registered. Please use a different email.");
    }

    // Insert user into database
    $insert_query = "INSERT INTO users (email, password, role) VALUES ('$email', '$hashed_password', '$role')";
    if (mysqli_query($conn, $insert_query)) {
        header("Location: login.php?success=Registration successful! Please log in.");
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>