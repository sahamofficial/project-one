<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
  $_SESSION['message'] = "You must be logged in and have items in your cart.";
  header("Location: ../../index.php");
  exit;
}

$userId = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

try {
  $dbh->beginTransaction();

  // Insert order
  $stmt = $dbh->prepare("INSERT INTO orders (user_id) VALUES (?)");
  $stmt->execute([$userId]);
  $orderId = $dbh->lastInsertId();

  // Insert order items
  $stmt = $dbh->prepare("INSERT INTO order_items (order_id, product_id) VALUES (?, ?)");
  foreach ($cart as $productId) {
    $stmt->execute([$orderId, $productId]);
  }

  $dbh->commit();

  // Clear cart
  unset($_SESSION['cart']);
  $_SESSION['message'] = "Order placed successfully!";
  header("Location: ../user/order-history.php");
  exit;
} catch (Exception $e) {
  $dbh->rollBack();
  $_SESSION['message'] = "Failed to complete order: " . $e->getMessage();
  header("Location: ../../index.php");
  exit;
}
?>
