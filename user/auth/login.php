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

<!-- HTML Login Form -->
<?php if (isset($_SESSION['error'])): ?>
  <div style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<form method="POST">
  <input type="text" name="contact_no" placeholder="Contact Number" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
  <a href="signup.php">Don't you have an account</a>
</form>
