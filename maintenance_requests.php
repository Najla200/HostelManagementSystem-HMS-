<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Maintenance Requests</title>
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

  // Fetch maintenance requests
  $requests_query = "SELECT id, student_id, description, status, reply 
                    FROM maintenance_requests 
                    ORDER BY id DESC"; // Changed to ORDER BY id DESC since created_at is removed
  $requests_result = mysqli_query($conn, $requests_query);
  if (!$requests_result) {
      die("Error fetching requests: " . mysqli_error($conn));
  }

  // Handle reply submission
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['reply'], $_POST['status'])) {
      $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);
      $reply = mysqli_real_escape_string($conn, $_POST['reply']);
      $status = mysqli_real_escape_string($conn, $_POST['status']);

      $update_query = "UPDATE maintenance_requests 
                       SET reply = '$reply', status = '$status' 
                       WHERE id = '$request_id'";
      if (mysqli_query($conn, $update_query)) {
          header("Location: maintenance_requests.php?success=Reply submitted successfully!");
          exit;
      } else {
          die("Error updating request: " . mysqli_error($conn));
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
        <li><a href="manageRooms.php">Manage Rooms</a></li>
        <li><a href="maintenance_requests.php" class="active">Maintenance Requests</a></li>
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
      <h2>Maintenance Requests</h2>
      <table>
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Description</th>
            <th>Status</th>
            <th>Reply</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($request = mysqli_fetch_assoc($requests_result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($request['student_id'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($request['description']); ?></td>
              <td><?php echo htmlspecialchars($request['status']); ?></td>
              <td><?php echo htmlspecialchars($request['reply'] ?? 'No reply yet'); ?></td>
              <td>
                <form action="maintenance_requests.php" method="POST" style="display:inline;">
                  <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                  <label for="status_<?php echo $request['id']; ?>">Status:</label>
                  <select name="status" id="status_<?php echo $request['id']; ?>" required>
                    <option value="Pending" <?php echo $request['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Resolved" <?php echo $request['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                  </select>
                  <label for="reply_<?php echo $request['id']; ?>">Reply:</label>
                  <textarea name="reply" id="reply_<?php echo $request['id']; ?>" rows="2" placeholder="Enter your reply"><?php echo htmlspecialchars($request['reply'] ?? ''); ?></textarea>
                  <input type="submit" value="Submit Reply">
                </form>
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
</body>
</html>