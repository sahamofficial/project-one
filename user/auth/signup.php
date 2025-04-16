<?php
require_once __DIR__ . '/../../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $contact_no = trim($_POST['contact_no']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  try {
    // Check if contact_no already exists
    $checkStmt = $dbh->prepare("SELECT id FROM users WHERE contact_no = :contact_no");
    $checkStmt->bindParam(':contact_no', $contact_no);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
      $_SESSION['error'] = "Contact number already exists.";
      header("Location: signup.php");
      exit;
    }

    // Insert new user
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

<!-- HTML Signup Form -->
<?php if (isset($_SESSION['error'])): ?>
  <div style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<form method="POST">
  <input type="text" name="name" placeholder="Name" required><br>
  <input type="text" name="contact_no" placeholder="Contact Number" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Sign Up</button>
</form>
