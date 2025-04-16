<?php
session_start();
require_once __DIR__ . '/../../config.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

$placeholders = rtrim(str_repeat('?,', count($cart)), ',');
$stmt = $dbh->prepare("SELECT id, name, image FROM products WHERE id IN ($placeholders)");
$stmt->execute($cart);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Your Cart</h2>
<ul>
<?php foreach ($products as $product): ?>
    <li>
        <img src="../productImg/<?php echo htmlentities($product['image']); ?>" width="50">
        <?php echo htmlentities($product['name']); ?>
    </li>
<?php endforeach; ?>
</ul>
<a href="checkout.php">Proceed to Checkout</a>
