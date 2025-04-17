<?php
session_start();
require_once __DIR__ . '/../../config.php';

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

// Check if user already liked this product
$checkStmt = $dbh->prepare("SELECT 1 FROM product_likes WHERE user_id = ? AND product_id = ?");
$checkStmt->execute([$userId, $productId]);
$userLiked = $checkStmt->fetch();

if ($userLiked) {
  // Unlike
  $deleteStmt = $dbh->prepare("DELETE FROM product_likes WHERE user_id = ? AND product_id = ?");
  $deleteStmt->execute([$userId, $productId]);
  $liked = false;
} else {
  // Like
  $insertStmt = $dbh->prepare("INSERT INTO product_likes (user_id, product_id) VALUES (?, ?)");
  $insertStmt->execute([$userId, $productId]);
  $liked = true;
}

// Updated like count
$countStmt = $dbh->prepare("SELECT COUNT(*) FROM product_likes WHERE product_id = ?");
$countStmt->execute([$productId]);
$likeCount = $countStmt->fetchColumn();

echo json_encode([
  'status' => 'success',
  'liked' => $liked,
  'likeCount' => $likeCount
]);
?>
