<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Edit Announcement</title>
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
      $announcement_id = mysqli_real_escape_string($conn, $_GET['id']);
      $announcement_query = "SELECT id, title, content 
                            FROM announcements 
                            WHERE id = '$announcement_id'";
      $announcement_result = mysqli_query($conn, $announcement_query);
      $announcement = mysqli_fetch_assoc($announcement_result);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $announcement_id = mysqli_real_escape_string($conn, $_POST['announcement_id']);
      $title = mysqli_real_escape_string($conn, $_POST['title']);
      $content = mysqli_real_escape_string($conn, $_POST['content']);

      $update_query = "UPDATE announcements 
                       SET title = '$title', content = '$content' 
                       WHERE id = '$announcement_id'";
      if (mysqli_query($conn, $update_query)) {
          header("Location: announcements.php?success=Announcement updated successfully!");
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
        <li><a href="manageRooms.php">Manage Rooms</a></li>
        <li><a href="maintenance_requests.php">Maintenance Requests</a></li>
        <li><a href="announcements.php" class="active">Announcements</a></li>
        <li><a href="entry_exit_logs.php">Entry/Exit Logs</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Edit Announcement</h2>
      <?php if (isset($_GET['error'])): ?>
        <p style="color: red; text-align: center;" id="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
      <?php endif; ?>
      <?php if (isset($announcement)): ?>
        <form action="edit_announcement.php" method="POST">
          <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
          <label for="title">Title:</label>
          <input type="text" name="title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
          <label for="content">Content:</label>
          <textarea name="content" rows="5" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
          <input type="submit" value="Update Announcement">
        </form>
      <?php else: ?>
        <p>Announcement not found.</p>
      <?php endif; ?>
    </main>
  </div>
  <?php mysqli_close($conn); ?>
  <?php include 'footer.php'; ?>
</body>
</html>