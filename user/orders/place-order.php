<?php
session_start();
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'] ?? 0;

  if (!$userId || empty($_SESSION['cart'])) {
    header('Location: ../../index.php');
    exit;
  }

  // Collect form data
  $name = $_POST['customer_name'];
  $email = $_POST['customer_email'];
  $phone = $_POST['customer_phone'];
  $address = $_POST['shipping_address'];
  $total = $_POST['total_amount'];

  // Insert into orders table
  $orderStmt = $dbh->prepare("INSERT INTO orders (user_id, total_amount, customer_name, customer_email, customer_phone, shipping_address) VALUES (?, ?, ?, ?, ?, ?)");
  $orderStmt->execute([$userId, $total, $name, $email, $phone, $address]);
  $orderId = $dbh->lastInsertId();

  // Insert into order_items
  foreach ($_SESSION['cart'] as $item) {
    $productId = $item['product_id'];
    $quantity = $item['quantity'];

    $productStmt = $dbh->prepare("SELECT price FROM products WHERE id = ?");
    $productStmt->execute([$productId]);
    $product = $productStmt->fetch();

    $itemPrice = $product['price'];

    $itemStmt = $dbh->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $itemStmt->execute([$orderId, $productId, $quantity, $itemPrice]);
  }

  // Clear cart
  unset($_SESSION['cart']);

  $_SESSION['order_success'] = "Order placed successfully! Order ID: $orderId";
  header('Location: order-success.php');
  exit;
}
