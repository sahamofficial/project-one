<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];

  $stmt = $dbh->prepare("UPDATE users SET name = :name WHERE id = :id");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $_SESSION['user_name'] = $name;
  $_SESSION['success'] = "Profile updated.";
  header("Location: profile.php");
  exit;
}

// Fetch current user info
$stmt = $dbh->prepare("SELECT name FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $user['name'];
?>

<!-- HTML Form -->
<?php include 'flash.php'; ?>
<form method="POST">
  <input type="text" name="name" value="<?php echo htmlentities($name); ?>" required><br>
  <button type="submit">Update</button>
</form>
