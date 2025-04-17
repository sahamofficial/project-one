<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
  header('Location: ' . BASE_URL . '/user/cart/cart.php');
  exit;
}

$userId = $_SESSION['user_id'];
$cartId = intval($_GET['id']);

$stmt = $dbh->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
$stmt->execute([$cartId, $userId]);

header('Location: ' . BASE_URL . '/user/cart/cart.php');
exit;
