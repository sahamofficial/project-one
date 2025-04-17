<?php
session_start();
require_once __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $productId = intval($_POST['product_id'] ?? 0);

  if ($productId <= 0) {
    $_SESSION['cart_error'] = 'Invalid product selected.';
    header('Location: ../../products.php');
    exit;
  }

  // Create cart array if it doesn't exist
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Add product or update quantity
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += 1;
  } else {
    $_SESSION['cart'][$productId] = [
      'product_id' => $productId,
      'quantity' => 1
    ];
  }

  $_SESSION['cart_success'] = 'Product added to cart.';
  header('Location: ../../products.php');
  exit;
}
