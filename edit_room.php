<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Edit Room</title>
  <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
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
      $room_query = "SELECT id, student_id, name, hostel_name, room_number 
                     FROM rooms 
                     WHERE id = '$room_id'"; // Updated query to match current schema
      $room_result = mysqli_query($conn, $room_query);
      $room = mysqli_fetch_assoc($room_result);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $room_id = mysqli_real_escape_string($conn, $_POST['room_id']);
      $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
      $hostel_name = mysqli_real_escape_string($conn, $_POST['hostel_name']);

      // Check if the new student_id is unique (excluding the current record)
      $check_student_id = "SELECT id FROM rooms WHERE student_id = '$student_id' AND id != '$room_id'";
      $result = mysqli_query($conn, $check_student_id);
      if (mysqli_num_rows($result) > 0) {
          header("Location: edit_room.php?id=$room_id&error=Student ID already assigned to another room.");
          exit;
      }

      $query = "UPDATE rooms 
                SET student_id = '$student_id', name = '$name', room_number = '$room_number', hostel_name = '$hostel_name' 
                WHERE id = '$room_id'";
      if (mysqli_query($conn, $query)) {
          header("Location: manageRooms.php?success=Room updated successfully!"); // Updated to manageRooms.php
          exit;
      } else {
          die("Error: " . mysqli_error($conn));
      }
  }
  ?>
  <header>
    <img src="logo.png" alt="Sabaragamuwa University Logo" class="logo">
    <div class="header-text">
      <h1>Hostel Management System (HMS)</h1>
      <p>Sabaragamuwa University of Sri Lanka</p>
    </div>
  </header>
  <div class="dashboard-container">
    <nav class="sidebar">
      <ul>
        <li><a href="manageRooms.php" class="active">Manage Rooms</a></li>
        <li><a href="maintenance_requests.php">Maintenance Requests</a></li>
        <li><a href="announcements.php">Announcements</a></li>
        <li><a href="entry_exit_logs.php">Entry/Exit Logs</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Edit Room</h2>
      <?php if (isset($_GET['error'])): ?>
        <p style="color: red; text-align: center;" id="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
      <?php endif; ?>
      <?php if (isset($room)): ?>
        <form action="edit_room.php" method="POST">
          <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
          <label for="student_id">Student ID:</label>
          <input type="text" name="student_id" value="<?php echo htmlspecialchars($room['student_id']); ?>" required>
          <label for="name">Name:</label>
          <input type="text" name="name" value="<?php echo htmlspecialchars($room['name']); ?>" required>
          <label for="room_number">Room Number:</label>
          <input type="text" name="room_number" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
          <label for="hostel_name">Hostel Name:</label>
          <select name="hostel_name" required>
            <option value="Walawa A" <?php echo $room['hostel_name'] === 'Walawa A' ? 'selected' : ''; ?>>Walawa A</option>
            <option value="Walawa B" <?php echo $room['hostel_name'] === 'Walawa B' ? 'selected' : ''; ?>>Walawa B</option>
            <option value="Walawa C" <?php echo $room['hostel_name'] === 'Walawa C' ? 'selected' : ''; ?>>Walawa C</option>
          </select>
          <input type="submit" value="Update Room">
        </form>
      <?php else: ?>
        <p>Room not found.</p>
      <?php endif; ?>
    </main>
  </div>
  <?php mysqli_close($conn); ?>
</body>
</html>