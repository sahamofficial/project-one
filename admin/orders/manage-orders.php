<?php
session_start();
require_once __DIR__ . '/../../config.php';

// Get filters
$contact = $_GET['contact_no'] ?? '';
$start = $_GET['start_date'] ?? '';
$end = $_GET['end_date'] ?? '';

// Base SQL
$sql = "
  SELECT o.id AS order_id, o.created_at, o.status, u.name AS user_name, u.contact_no, p.name AS product_name, p.price
  FROM orders o
  JOIN users u ON o.user_id = u.id
  JOIN order_items oi ON o.id = oi.order_id
  JOIN products p ON oi.product_id = p.id
  WHERE 1=1
";

// Conditions
$params = [];
if ($contact) {
  $sql .= " AND u.contact_no LIKE ?";
  $params[] = "%$contact%";
}
if ($start) {
  $sql .= " AND DATE(o.created_at) >= ?";
  $params[] = $start;
}
if ($end) {
  $sql .= " AND DATE(o.created_at) <= ?";
  $params[] = $end;
}

$sql .= " ORDER BY o.created_at DESC";

$stmt = $dbh->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_GROUP);
?>

<?php include(__DIR__ . '/../includes/header.php'); ?>

<h2>Manage Orders</h2>

<form method="GET" style="margin-bottom: 1em;">
  <input type="text" name="contact_no" placeholder="Contact No" value="<?= htmlentities($contact) ?>">
  <input type="date" name="start_date" value="<?= htmlentities($start) ?>">
  <input type="date" name="end_date" value="<?= htmlentities($end) ?>">
  <button type="submit">Filter</button>
</form>