<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Entry/Exit Logs</title>
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

  // Fetch entry/exit logs with student name
  $logs_query = "SELECT e.id, e.student_id, r.name, e.time_left, e.time_returned 
                 FROM entry_exit_logs e 
                 LEFT JOIN rooms r ON e.student_id = r.student_id 
                 ORDER BY e.time_left DESC";
  $logs_result = mysqli_query($conn, $logs_query);
  if (!$logs_result) {
      die("Error fetching logs: " . mysqli_error($conn));
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
        <li><a href="manageRooms.php">Manage Rooms</a></li>
        <li><a href="maintenance_requests.php">Maintenance Requests</a></li>
        <li><a href="announcements.php">Announcements</a></li>
        <li><a href="entry_exit_logs.php" class="active">Entry/Exit Logs</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Entry/Exit Logs</h2>
      <table>
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Time Left</th>
            <th>Time Returned</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($log = mysqli_fetch_assoc($logs_result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($log['student_id'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($log['name'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($log['time_left']); ?></td>
              <td><?php echo htmlspecialchars($log['time_returned'] ?? 'Not Returned'); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
  <?php mysqli_close($conn); ?>
</body>
</html>