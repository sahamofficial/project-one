<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $oldPassword = $_POST['old_password'];
  $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
  $stmt->bind_param("i", $_SESSION['user_id']);
  $stmt->execute();
  $stmt->bind_result($dbPassword);
  $stmt->fetch();
  $stmt->close();

  if (password_verify($oldPassword, $dbPassword)) {
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $_SESSION['user_id']);
    $stmt->execute();
    $_SESSION['success'] = "Password updated.";
  } else {
    $_SESSION['error'] = "Old password is incorrect.";
  }

  header("Location: change-password.php");
  exit;
}
?>

<!-- HTML Form -->
<?php include 'flash.php'; ?>
<h3>Change Password</h3>
<form method="POST">
  <input type="password" name="old_password" placeholder="Old Password" required><br>
  <input type="password" name="new_password" placeholder="New Password" required><br>
  <button type="submit">Update Password</button>
</form>
