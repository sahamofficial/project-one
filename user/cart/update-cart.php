<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/user/cart/cart.php');
    exit;
}

$userId = $_SESSION['user_id'];
$quantities = $_POST['quantities'] ?? [];

foreach ($quantities as $cartId => $qty) {
    $qty = max(1, intval($qty)); // Ensure quantity is at least 1
    $stmt = $dbh->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$qty, $cartId, $userId]);
}

header('Location: ' . BASE_URL . '/user/cart/cart.php');
exit;
