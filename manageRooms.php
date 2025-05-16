<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Manage Rooms</title>
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

  // Query the rooms table directly (no join needed)
  $rooms_query = "SELECT id, student_id, name, hostel_name, room_number 
                  FROM rooms";
  $rooms_result = mysqli_query($conn, $rooms_query);
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
      <?php if (isset($_GET['success'])): ?>
        <p class="success-message" id="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
      <?php endif; ?>
      <?php if (isset($_GET['error'])): ?>
        <p style="color: red; text-align: center;" id="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
      <?php endif; ?>
      <h3>Add New Room Assignment</h3>
      <form action="add_room.php" method="POST">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" required>
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="hostel_name">Hostel Name:</label>
        <select name="hostel_name" required>
          <option value="Walawa A">Walawa A</option>
          <option value="Walawa B">Walawa B</option>
          <option value="Walawa C">Walawa C</option>
        </select>
        <label for="room_number">Room Number:</label>
        <input type="text" name="room_number" required>
        <input type="submit" value="Add">
      </form>
    
      <table>
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Hostel Name</th>
            <th>Room No</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($room = mysqli_fetch_assoc($rooms_result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($room['student_id'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($room['name'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($room['hostel_name']); ?></td>
              <td><?php echo htmlspecialchars($room['room_number']); ?></td>
              <td>
                <div class="button-group">
                  <a href="edit_room.php?id=<?php echo $room['id']; ?>" class="btn btn-edit">Edit</a>
                  <a href="delete_room.php?id=<?php echo $room['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this room assignment?');">Delete</a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
  <?php mysqli_close($conn); ?>
  <script>
    // Hide success message after 5 seconds
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
      setTimeout(() => {
        successMessage.style.display = 'none';
      }, 5000); // 5000 milliseconds = 5 seconds
    }

    // Hide error message after 5 seconds
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
      setTimeout(() => {
        errorMessage.style.display = 'none';
      }, 5000); // 5000 milliseconds = 5 seconds
    }
  </script>
  <?php include 'footer.php'; ?>
</body>
</html>