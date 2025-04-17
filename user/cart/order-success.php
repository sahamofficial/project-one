<?php
session_start();
require_once '../../config.php';
require_once '../includes/header.php';

$message = $_SESSION['order_success'] ?? 'No recent order.';
unset($_SESSION['order_success']);
?>

<div class="container mt-5">
  <h3><?= htmlentities($message) ?></h3>
  <a href="../../index.php" class="btn btn-primary mt-3">Continue Shopping</a>
</div>

<?php require_once '../includes/footer.php'; ?>
