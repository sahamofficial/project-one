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

  $stmt = $dbh->prepare("UPDATE users SET name = :name WHERE id = :id");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $_SESSION['user_name'] = $name;
  $_SESSION['success'] = "Profile updated.";
  header("Location: profile.php");
  exit;
}

$stmt = $dbh->prepare("SELECT name FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $user['name'];
?>

<style>
  .edit-profile-container {
    max-width: 500px;
    margin: 40px auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', sans-serif;
  }

  .edit-profile-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
  }

  .edit-profile-container form {
    display: flex;
    flex-direction: column;
  }

  .edit-profile-container input[type="text"] {
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    outline: none;
    transition: border 0.3s ease;
  }

  .edit-profile-container input[type="text"]:focus {
    border-color: #007BFF;
  }

  .edit-profile-container button {
    padding: 12px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .edit-profile-container button:hover {
    background-color: #0056b3;
  }

  @media (max-width: 480px) {
    .edit-profile-container {
      margin: 20px;
      padding: 20px;
    }

    .edit-profile-container input[type="text"],
    .edit-profile-container button {
      font-size: 14px;
    }
  }
</style>

<div class="edit-profile-container">
  <h2>Edit Profile</h2>
  <form method="POST">
    <input type="text" name="name" value="<?php echo htmlentities($name); ?>" required>
    <button type="submit">Update</button>
  </form>
</div>
