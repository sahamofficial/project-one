<?php
session_start();
include(__DIR__ . '/user/includes/products-header.php');

// Fetch products from DB
$sql = "SELECT 
          p.*, 
          c.name AS category_name,
          (SELECT COUNT(*) FROM product_likes WHERE product_id = p.id) AS like_count
        FROM products p
        LEFT JOIN categories c ON p.catid = c.id
        ORDER BY p.id DESC";
$query = $dbh->prepare($sql);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
    
<body>

    <main class="main">
        <section id="products" class="products section">
            <!-- Section title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>All Products</h2>
                <div class="title-shape">
                    <svg viewBox="0 0 200 20">
                        <path d="M 0,10 C 40,0 60,20 100,10 C 140,0 160,20 200,10" fill="none" stroke="currentColor"
                            stroke-width="2" />
                    </svg>
                </div>
            </div>

            <!-- Product grid -->
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4 isotope-container" data-aos="fade-up" data-aos-delay="300">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php
                            $userLiked = false;
                            if (isset($_SESSION['user_id'])) {
                                $checkLiked = $dbh->prepare("SELECT 1 FROM product_likes WHERE user_id = ? AND product_id = ?");
                                $checkLiked->execute([$_SESSION['user_id'], $product->id]);
                                $userLiked = $checkLiked->fetch();
                            }
                            ?>
                            <div
                                class="col-lg-4 col-md-6 products-item isotope-item filter-<?php echo strtolower(str_replace(' ', '-', $product->category_name)); ?>">
                                <div class="products-card">
                                    <div class="products-image position-relative">
                                        <img src="<?= url('admin\productImg\\') ?><?php echo htmlentities($product->image ?? 'default.png'); ?>"
                                            class="img-fluid" alt="<?php echo htmlentities($product->name); ?>" loading="lazy"
                                            onerror="this.src='<?= url('admin/productImg/default.png') ?>';">

                                        <div class="products-overlay">
                                            <div class="products-actions d-flex justify-content-between align-items-center">
                                                <a href="<?= url('admin\productImg\\') ?><?php echo htmlentities($product->image); ?>"
                                                    class="glightbox preview-link"><i class="bi bi-eye"></i></a>
                                                <a href="products-details.php?pid=<?php echo htmlentities($product->id); ?>"
                                                    class="details-link"><i class="bi bi-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="products-content">
                                        <span class="category"><?php echo htmlentities($product->category_name); ?></span>
                                        <h3><?php echo htmlentities($product->name); ?></h3>
                                        <p><?php echo htmlentities(mb_strimwidth($product->description ?? '', 0, 100, '...')); ?>
                                        </p>

                                        <div class="d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-sm like-btn <?= $userLiked ? 'btn-danger' : 'btn-outline-danger' ?>"
                                                data-id="<?= $product->id ?>" data-liked="<?= $userLiked ? '1' : '0' ?>">
                                                <i class="bi <?= $userLiked ? 'bi-heart-fill text-danger' : 'bi-heart' ?>"></i>
                                                <span class="like-count"><?= htmlentities($product->like_count) ?></span>
                                            </button>

                                            <form method="post" action="user/cart/add-to-cart.php"
                                                class="d-inline add-to-cart-form">
                                                <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                                <button type="submit" class="btn btn-sm btn-primary mt-2 add-to-cart-btn">
                                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                                </button>
                                            </form>
                                            <div class="cart-feedback mt-1" style="font-size: 0.875rem;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">No products available.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-chevron-double-up"></i>
    </a>

    <?php include(__DIR__ . '/user/includes/footer.php'); ?>

</body>

</html>