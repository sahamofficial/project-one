<?php
require_once __DIR__ . '/../../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $contact_no = trim($_POST['contact_no']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  try {
    $checkStmt = $dbh->prepare("SELECT id FROM users WHERE contact_no = :contact_no");
    $checkStmt->bindParam(':contact_no', $contact_no);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
      $_SESSION['error'] = "Contact number already exists.";
      header("Location: signup.php");
      exit;
    }

    $stmt = $dbh->prepare("INSERT INTO users (name, contact_no, password) VALUES (:name, :contact_no, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact_no', $contact_no);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
      $_SESSION['user_id'] = $dbh->lastInsertId();
      $_SESSION['user_name'] = $name;
      $_SESSION['success'] = "Account created successfully!";
      header("Location: profile.php");
      exit;
    } else {
      $_SESSION['error'] = "Failed to create account.";
      header("Location: signup.php");
      exit;
    }

  } catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: signup.php");
    exit;
  }
}
?>
<?php include(__DIR__ . '/../includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  </style>
</head>
<body>

  <div class="signup-container">
    <h2>Create Account</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="name" placeholder="Name" required>
      <input type="text" name="contact_no" placeholder="Contact Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Sign Up</button>
    </form>
  </div>

</body>
</html>
