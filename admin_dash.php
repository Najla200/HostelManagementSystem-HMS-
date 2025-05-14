<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
  <?php
  session_start();
  // Check if user is logged in and is an admin
  if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
      header("Location: login.php");
      exit;
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
        <li><a href="entry_exit_logs.php">Entry/Exit Logs</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Welcome, Admin!</h2>
      <?php if (isset($_GET['success'])): ?>
        <p class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>