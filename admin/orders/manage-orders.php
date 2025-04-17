<?php
require_once '../includes/header.php';

$orderStmt = $dbh->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$orderStmt->execute();
$orders = $orderStmt->fetchAll();
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-5">
  <h3>Manage Orders</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>User</th>
        <th>Total</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
        <tr>
          <td><?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['customer_name']) ?></td>
          <td>$<?= number_format($order['total_amount'], 2) ?></td>
          <td>
            <form method="post" action="update-order-status.php" class="d-inline">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <select name="status" onchange="this.form.submit()">
                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
              </select>
            </form>
          </td>
          <td>
            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $order['id'] ?>)">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Hidden form for deletion -->
  <form id="deleteForm" method="post" action="delete-order.php" style="display: none;">
    <input type="hidden" name="order_id" id="deleteOrderId">
  </form>
</div>

<script>
function confirmDelete(orderId) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This will permanently delete the order.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('deleteOrderId').value = orderId;
      document.getElementById('deleteForm').submit();
    }
  });
}
</script>

<?php require_once '../includes/footer.php'; ?>
