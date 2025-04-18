<?php
session_start();
require_once '../../config.php';
require_once '../includes/products-header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/user/auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch orders
$stmt = $dbh->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head content -->
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="content container mt-5">
            <h3>Your Order History</h3>

            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>Order #<?= $order['id'] ?></strong> |
                            Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?> |
                            <span class="badge bg-info"><?= htmlentities($order['status']) ?></span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group mb-2">
                                <?php
                                $itemStmt = $dbh->prepare("
                            SELECT oi.quantity, oi.price, p.name 
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.id
                            WHERE oi.order_id = ?
                        ");
                                $itemStmt->execute([$order['id']]);
                                $items = $itemStmt->fetchAll();

                                foreach ($items as $item):
                                    $subtotal = $item['price'] * $item['quantity'];
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span><?= htmlentities($item['name']) ?> (x<?= $item['quantity'] ?>)</span>
                                        <span>Rs.<?= number_format($subtotal, 2) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <strong>Total: Rs.<?= number_format($order['total_amount'], 2) ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">You have not placed any orders yet.</div>
            <?php endif; ?>
        </div>

        <?php require_once '../includes/footer.php'; ?>
    </div>
</body>

</html>