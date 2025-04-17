<?php
session_start();
require_once '../../config.php';

if (!isset($_SESSION['alogin']) || $_SESSION['alogin'] == '') {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];

    // Optional: delete related data (e.g., order_items) first if needed
    $dbh->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$orderId]);
    $dbh->prepare("DELETE FROM orders WHERE id = ?")->execute([$orderId]);

    $_SESSION['success'] = "Order #$orderId deleted successfully.";
}

header('Location: manage-orders.php');
exit;
