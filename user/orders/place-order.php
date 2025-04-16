<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../user/login.php");
  exit;
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
  $_SESSION['message'] = "Cart is empty.";
  header("Location: cart.php");
  exit;
}

$userId = $_SESSION['user_id'];

// Insert a basic order
try {
  $dbh->beginTransaction();

  // Insert order
  $stmt = $dbh->prepare("INSERT INTO orders (user_id, created_at) VALUES (?, NOW())");
  $stmt->execute([$userId]);
  $orderId = $dbh->lastInsertId();

  // Insert order items
  $stmtItem = $dbh->prepare("INSERT INTO order_items (order_id, product_id) VALUES (?, ?)");
  foreach ($cart as $productId) {
    $stmtItem->execute([$orderId, $productId]);
  }

  $dbh->commit();
  unset($_SESSION['cart']);
  $_SESSION['message'] = "Order placed successfully!";
} catch (PDOException $e) {
  $dbh->rollBack();
  $_SESSION['message'] = "Order failed: " . $e->getMessage();
}

header("Location: cart.php");
exit;
