<?php
include __DIR__ . '/../../config.php';
session_start();

if (!isset($_SESSION['alogin']) || $_SESSION['alogin'] == '') {
  header('Location: ../auth/login.php');
  exit;
}

$orderId = intval($_POST['order_id'] ?? 0);
$status = $_POST['status'] ?? 'pending';

if ($orderId > 0) {
  $stmt = $dbh->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->execute([$status, $orderId]);
}

header("Location: manage-orders.php");
exit;
