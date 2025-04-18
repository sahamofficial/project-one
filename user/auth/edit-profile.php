<?php
session_start();
require_once __DIR__ . '/../../config.php';
include(__DIR__ . '/../includes/header.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $contact_no = $_POST['contact_no'];
  $password = $_POST['password'];

  if (!empty($password)) {
    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $dbh->prepare("UPDATE users SET name = :name, contact_no = :contact_no, password = :password WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact_no', $contact_no);
    $stmt->bindParam(':password', $hashed_password);
  } else {
    $stmt = $dbh->prepare("UPDATE users SET name = :name, contact_no = :contact_no WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact_no', $contact_no);
  }

  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $_SESSION['user_name'] = $name;
  $_SESSION['success'] = "Profile updated.";
  header("Location: profile.php");
  exit;
}

$stmt = $dbh->prepare("SELECT name, contact_no FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $user['name'];
$contact_no = $user['contact_no'];
?>

<div class="edit-profile-container">
  <h2>Edit Profile</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlentities($name); ?>" required>
    <input type="tel" name="contact_no" placeholder="Contact Number" value="<?php echo htmlentities($contact_no); ?>" required>
    <input type="password" name="password" placeholder="New Password (leave blank to keep unchanged)">
    <button type="submit">Update</button>
  </form>
</div>
