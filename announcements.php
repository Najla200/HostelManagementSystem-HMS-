<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Announcements</title>
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

  // Handle new announcement submission
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
      $title = mysqli_real_escape_string($conn, $_POST['title']);
      $content = mysqli_real_escape_string($conn, $_POST['content']);

      $insert_query = "INSERT INTO announcements (title, content) VALUES ('$title', '$content')";
      if (mysqli_query($conn, $insert_query)) {
          header("Location: announcements.php?success=Announcement posted successfully!");
          exit;
      } else {
          die("Error posting announcement: " . mysqli_error($conn));
      }
  }

  // Fetch existing announcements
  $announcements_query = "SELECT id, title, content 
                         FROM announcements 
                         ORDER BY id DESC";
  $announcements_result = mysqli_query($conn, $announcements_query);
  if (!$announcements_result) {
      die("Error fetching announcements: " . mysqli_error($conn));
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
      <?php if (isset($_GET['success'])): ?>
        <p class="success-message" id="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
      <?php endif; ?>
      <?php if (isset($_GET['error'])): ?>
        <p style="color: red; text-align: center;" id="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
      <?php endif; ?>
      <h2>Post New Announcement</h2>
      <form action="announcements.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" required>
        <label for="content">Content:</label>
        <textarea name="content" rows="5" required placeholder="Enter the announcement content"></textarea>
        <input type="submit" value="Post Announcement">
      </form>
      <h2>Existing Announcements</h2>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($announcement = mysqli_fetch_assoc($announcements_result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($announcement['title']); ?></td>
              <td><?php echo htmlspecialchars($announcement['content']); ?></td>
              <td>
                <div class="button-group">
                  <a href="edit_announcement.php?id=<?php echo $announcement['id']; ?>" class="btn btn-edit">Edit</a>
                  <a href="delete_announcement.php?id=<?php echo $announcement['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</a>
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
</body>
</html>