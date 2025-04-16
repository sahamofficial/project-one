<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../user/login.php");
  exit;
}

$userId = $_SESSION['user_id'];

$stmt = $dbh->prepare("
  SELECT o.id, o.status, o.created_at, GROUP_CONCAT(p.name SEPARATOR ', ') AS products
  FROM orders o
  JOIN order_items oi ON o.id = oi.order_id
  JOIN products p ON oi.product_id = p.id
  WHERE o.user_id = ?
  GROUP BY o.id
  ORDER BY o.created_at DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Your Order History</h2>
<?php foreach ($orders as $order): ?>
  <div>
    <strong>Order #<?= $order['id'] ?></strong><br>
    Status: <?= $order['status'] ?><br>
    Products: <?= htmlentities($order['products']) ?><br>
    Date: <?= $order['created_at'] ?><br><br>
  </div>
<?php endforeach; ?>
