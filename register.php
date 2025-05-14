<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Registration</title>
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
  <form action="registration_process.php" method="POST">
    <h2>Registration</h2>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
      <option value="student">Student</option>
      <option value="admin">Admin</option>
    </select><br><br>
    <input type="submit" value="Register">
    <p>Already registered? <a href="login.php">Login here</a></p>
  </form>
</body>
</html>
