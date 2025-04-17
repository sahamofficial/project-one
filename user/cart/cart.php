<?php
session_start();
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/user/auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $dbh->prepare("
  SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image 
  FROM cart_items c
  JOIN products p ON c.product_id = p.id
  WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

$_SESSION['cart'] = $cartItems; // Store cart in session as well

$total = 0;
?>

<body class="container mt-4">
    <h2>Your Shopping Cart</h2>
    <?php if (count($cartItems) > 0): ?>
        <!-- Form to update cart -->
        <form action="update-cart.php" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlentities($item['name']) ?></td>
                            <td>
                                <img src="<?= BASE_URL . '/admin/productImg/' . htmlentities($item['image']) ?>" loading="lazy"
                                    width="50" alt="Product Image"
                                    onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/img/fallback.jpg';">
                            </td>

                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>"
                                    min="1" class="form-control" style="width: 80px;">
                            </td>
                            <td>$<?= number_format($subtotal, 2) ?></td>
                            <td>
                                <a href="remove-from-cart.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h4>Total: $<?= number_format($total, 2) ?></h4>
            <button type="submit" class="btn btn-success">Update Cart</button>
        </form>

        <!-- Checkout button outside the update cart form -->
        <form method="post" action="checkout.php">
            <button type="submit" class="btn btn-success btn-lg mt-3">Proceed to Checkout</button>
        </form>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>

</html>
