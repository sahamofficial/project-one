<?php
session_start();
require_once __DIR__ . '/../../config.php';


if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];

$stmt = $dbh->prepare("SELECT name, contact_no FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $user['name'] ?? '';
$contact_no = $user['contact_no'] ?? '';
?>

<?php include(__DIR__ . '/../includes/header.php'); ?>

<h2>Welcome, <?php echo htmlentities($name); ?></h2>
<p>Contact No: <?php echo htmlentities($contact_no); ?></p>
<a href="edit-profile.php">Edit Profile</a> |
<a href="change-password.php">Change Password</a> |
<a href="delete-profile.php" onclick="return confirm('Delete account permanently?');">Delete Profile</a> |
<a href="logout.php">Logout</a>