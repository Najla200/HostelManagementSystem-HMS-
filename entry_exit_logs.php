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

  // Fetch entry/exit logs with student name, grouped by date
  $logs_query = "SELECT DATE(e.time_left) AS log_date, e.id, e.student_id, r.name, e.time_left, e.time_returned 
                 FROM entry_exit_logs e 
                 LEFT JOIN rooms r ON e.student_id = r.student_id 
                 ORDER BY DATE(e.time_left) DESC, e.time_left DESC";
  $logs_result = mysqli_query($conn, $logs_query);
  if (!$logs_result) {
      die("Error fetching logs: " . mysqli_error($conn));
  }

  // Organize logs by date
  $logs_by_date = [];
  while ($log = mysqli_fetch_assoc($logs_result)) {
      $date = $log['log_date'];
      if (!isset($logs_by_date[$date])) {
          $logs_by_date[$date] = [];
      }
      $logs_by_date[$date][] = $log;
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
      <?php if (empty($logs_by_date)): ?>
        <p>No entry/exit logs available.</p>
      <?php else: ?>
        <?php foreach ($logs_by_date as $date => $logs): ?>
          <h3><?php echo htmlspecialchars(date('F j, Y', strtotime($date))); ?></h3>
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
              <?php foreach ($logs as $log): ?>
                <tr>
                  <td><?php echo htmlspecialchars($log['student_id'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($log['name'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($log['time_left']); ?></td>
                  <td><?php echo htmlspecialchars($log['time_returned'] ?? 'Not Returned'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endforeach; ?>
      <?php endif; ?>
    </main>
  </div>
  <?php mysqli_close($conn); ?>
  <?php include 'footer.php'; ?>
</body>
</html>