<?php
require_once __DIR__ . '/../../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $contact_no = $_POST['contact_no'];
  $password = $_POST['password'];

  $stmt = $dbh->prepare("SELECT id, name, password FROM users WHERE contact_no = :contact_no");
  $stmt->bindParam(':contact_no', $contact_no);
  $stmt->execute();

  if ($stmt->rowCount() === 1) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      header("Location: ../../index.php");
      exit;
    }
  }

  $_SESSION['error'] = "Invalid contact number or password.";
  header("Location: login.php");
  exit;
}
?>
<?php include(__DIR__ . '/../includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

  <div class="login-container">
    <h2>Login</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="error-message"><?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="contact_no" placeholder="Contact Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
      <a href="signup.php">Don't have an account?</a>
    </form>
  </div>

</body>

</html>