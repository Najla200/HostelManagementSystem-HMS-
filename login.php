<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <img src="logo.png" alt="Sabaragamuwa University Logo" class="logo">
    <div class="header-text">
      <h1>Hostel Management System (HMS)</h1>
      <p>Sabaragamuwa University of Sri Lanka</p>
    </div>
  </header>
  <form action="login_process.php" method="POST">
    <h2>Login</h2>
    <?php if (isset($_GET['success'])): ?>
      <p class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
      <p class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>
  <?php include 'footer.php'; ?>
</body>
</html>