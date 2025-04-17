<?php
session_start();
require_once '../../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'redirect',
        'url' => BASE_URL . '/user/auth/login.php'
    ]);
    exit;
}

$userId = $_SESSION['user_id'];
$productId = intval($_POST['product_id'] ?? 0);

if ($productId <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid product.'
    ]);
    exit;
}

// Check if item already in cart
$checkStmt = $dbh->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
$checkStmt->execute([$userId, $productId]);
$existing = $checkStmt->fetch();

if ($existing) {
    $updateStmt = $dbh->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $updateStmt->execute([$userId, $productId]);
} else {
    $insertStmt = $dbh->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insertStmt->execute([$userId, $productId]);
}

// Fetch updated cart items and store in session
$cartStmt = $dbh->prepare("SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image FROM cart_items c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$cartStmt->execute([$userId]);
$cartItems = $cartStmt->fetchAll();

$_SESSION['cart'] = $cartItems;

// Get updated cart count
$countStmt = $dbh->prepare("SELECT COUNT(*) FROM cart_items WHERE user_id = ?");
$countStmt->execute([$userId]);
$cartCount = $countStmt->fetchColumn();

echo json_encode([
    'status' => 'success',
    'message' => 'Product added to cart.',
    'cartCount' => $cartCount
]);
