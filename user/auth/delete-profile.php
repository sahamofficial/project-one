<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];

$stmt = $dbh->prepare("DELETE FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

session_destroy();
header("Location: signup.php");
exit;
