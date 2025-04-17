<?php
session_start();
require_once '../../config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='container mt-4'><h4>Your cart is empty.</h4></div>";
    exit;
}

?>

<div class="container mt-5">
    <h3>Checkout</h3>

    <div class="alert alert-warning" role="alert">
        ⚠️ <strong>Important:</strong> Please make sure the information you provide is accurate. If the name, phone
        number, or shipping address is incorrect, your order may be delayed or lost.
    </div>

    <form action="place-order.php" method="post">
        <div class="row">
            <div class="col-md-6">
                <h5>Shipping Details</h5>
                <div class="form-group mb-2">
                    <label>Name</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label>Phone</label>
                    <input type="text" name="customer_phone" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label>Address</label>
                    <textarea name="shipping_address" class="form-control" required></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <h5>Order Summary</h5>
                <ul class="list-group">
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item) {
                        $productId = $item['product_id'];
                        $stmt = $dbh->prepare("SELECT name, price FROM products WHERE id = ?");
                        $stmt->execute([$productId]);
                        $product = $stmt->fetch();
                        $subtotal = $product['price'] * $item['quantity'];
                        $total += $subtotal;
                        echo "<li class='list-group-item'>{$product['name']} (x{$item['quantity']}) = Rs." . number_format($subtotal, 2) . "</li>";
                    }
                    ?>
                    <li class="list-group-item active">Total: Rs.<?= number_format($total, 2) ?></li>
                </ul>
                <input type="hidden" name="total_amount" value="<?= $total ?>">
                <button type="submit" class="btn btn-success mt-3 w-100">Place Order</button>
            </div>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
