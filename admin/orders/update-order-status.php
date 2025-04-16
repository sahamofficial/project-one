<?php
require_once __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $orderId = $_POST['order_id'];
  $status = $_POST['status'];

  $stmt = $dbh->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->execute([$status, $orderId]);
}

header("Location: admin-orders.php");
exit;
