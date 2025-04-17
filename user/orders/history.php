<?php
session_start();
require_once '../../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your order history.";
    exit;
}

$userId = $_SESSION['user_id'];
$orderStmt = $dbh->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orderStmt->execute([$userId]);
$orders = $orderStmt->fetchAll();

?>

<div class="container mt-5">
  <h3>Your Order History</h3>
  <?php if ($orders): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Total</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?= $order['id'] ?></td>
            <td>$<?= number_format($order['total_amount'], 2) ?></td>
            <td><?= ucfirst($order['status']) ?></td>
            <td><?= date('Y-m-d', strtotime($order['created_at'])) ?></td>
            <td>
              <a href="invoice.php?order_id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">View Invoice</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No orders found.</p>
  <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
