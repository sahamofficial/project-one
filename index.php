<?php include(__DIR__ . '/user/includes/header.php');

// Fetch categories (if your UI uses them)
$sql = "SELECT * FROM categories";
$query = $dbh->prepare($sql);
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_OBJ);

// Fetch latest products (limit 8)
try {
  $sql = "SELECT 
            p.*, 
            c.name AS category_name,
            (SELECT COUNT(*) FROM product_likes WHERE product_id = p.id) AS like_count
          FROM products p
          LEFT JOIN categories c ON p.catid = c.id
          ORDER BY p.id DESC
          LIMIT 8";

  $query = $dbh->prepare($sql);
  $query->execute();
  $products = $query->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
  echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
  $products = [];
}
?>


<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row align-items-center content">
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
          <h2>Elegant Floral Creations for Every Occasion</h2>
          <p class="lead">Explore our handcrafted collection of floral wreaths, jewellery, keychains, pearl balls, and
            wedding accessories. Designed with love, our pieces add a touch of beauty to your everyday style and
            special moments. High quality at affordable prices</p>
          <div class="cta-buttons" data-aos="fade-up" data-aos-delay="300">
            <a href="#products" class="btn btn-primary">View My Work</a>
            <a href="#contact" class="btn btn-outline">Let's Connect</a>
          </div>
          <div class="hero-stats" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-item">
              <span class="stat-number">20+</span>
              <span class="stat-label">Years Experience</span>
            </div>
            <div class="stat-item">
              <span class="stat-number">100+</span>
              <span class="stat-label">Oders Completed</span>
            </div>
            <div class="stat-item">
              <span class="stat-number">500+</span>
              <span class="stat-label">Happy Clients</span>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-image">
            <img src="assets/img/profile/3227805.png" alt="products Hero Image" class="img-fluid" data-aos="zoom-out"
              data-aos-delay="300">
            <div class="shape-1"></div>
            <div class="shape-2"></div>
          </div>
        </div>
      </div>

    </div>

  </section><!-- /Hero Section -->

  <!-- products Section -->
  <section id="products" class="products section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Products</h2>
      <div class="title-shape">
        <svg viewBox="0 0 200 20" xmlns="http://www.w3.org/2000/svg">
          <path d="M 0,10 C 40,0 60,20 100,10 C 140,0 160,20 200,10" fill="none" stroke="currentColor" stroke-width="2">
          </path>
        </svg>
      </div>
      <p>Discover a world of everlasting beauty with our artfully designed collection. Each piece is lovingly crafted
        to add a touch of floral charm to your everyday style and special moments—all at affordable prices.</p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

        <!-- Products Filters -->
        <div class="products-filters-container" data-aos="fade-up" data-aos-delay="200">
          <ul class="products-filters isotope-filters">
            <li data-filter="*" class="filter-active">All Products</li>
            <?php foreach ($categories as $category): ?>
              <li data-filter=".filter-<?php echo strtolower(str_replace(' ', '-', $category->name)); ?>">
                <?php echo htmlentities($category->name); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Products Listing -->
        <div class="row g-4 isotope-container" data-aos="fade-up" data-aos-delay="300">
          <?php foreach ($products as $product): ?>
            <?php
            $countStmt = $dbh->prepare("SELECT COUNT(*) FROM product_likes WHERE product_id = ?");
            $countStmt->execute([$product->id]);
            $likeCount = $countStmt->fetchColumn();

            $userLiked = false;
            if (isset($_SESSION['user_id'])) {
              $checkLiked = $dbh->prepare("SELECT 1 FROM product_likes WHERE user_id = ? AND product_id = ?");
              $checkLiked->execute([$_SESSION['user_id'], $product->id]);
              $userLiked = $checkLiked->fetch();
            }
            ?>
            <div
              class="col-lg-6 col-md-6 products-item isotope-item filter-<?php echo strtolower(str_replace(' ', '-', $product->category_name)); ?>">
              <div class="products-card">
                <div class="products-image position-relative">
                  <img src="admin/productImg/<?php echo htmlentities($product->image ?? 'default.png'); ?>"
                    class="img-fluid" alt="<?php echo htmlentities($product->name); ?>" loading="lazy"
                    onerror="this.src='admin/productImg/default.png';">

                  <div class="products-overlay">
                    <div class="products-actions d-flex justify-content-between align-items-center">
                      <a href="admin/productImg/<?php echo htmlentities($product->image); ?>"
                        class="glightbox preview-link">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="all-products.php?pid=<?php echo htmlentities($product->id); ?>" class="details-link">
                        <i class="bi bi-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="products-content">
                  <span class="category"><?php echo htmlentities($product->category_name); ?></span>
                  <h3><?php echo htmlentities($product->name); ?></h3>
                  <p><?php echo htmlentities(mb_strimwidth($product->description ?? '', 0, 100, '...')); ?></p>

                  <!-- Like & Add to Cart Buttons -->
                  <div class="d-flex gap-2">
                    <button type="button"
                      class="btn btn-sm like-btn <?= $userLiked ? 'btn-danger' : 'btn-outline-danger' ?>"
                      data-id="<?= $product->id ?>" data-liked="<?= $userLiked ? '1' : '0' ?>">
                      <i class="bi <?= $userLiked ? 'bi-heart-fill text-danger' : 'bi-heart' ?>"></i>
                      <span class="like-count"><?= htmlentities($likeCount) ?></span>
                    </button>

                    <!-- Add to Cart Button -->
                    <form method="post" action="user/cart/add-to-cart.php" class="d-inline add-to-cart-form">
                      <input type="hidden" name="product_id" value="<?= $product->id ?>">
                      <button type="submit" class="btn btn-sm btn-primary mt-2 add-to-cart-btn">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                      </button>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
          <a href="<?= url('all-products.php') ?>" class="btn btn-outline-primary">View All Products</a>
        </div>

        <!-- End products Container -->

      </div>

    </div>

  </section>

  <!-- end products Section -->

  <!-- Testimonials Section -->
  <!-- A testimonial is a statement from a past customer that describes how a product or service helped them -->
  <section id="testimonials" class="testimonials section light-background">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Testimonials</h2>
      <div class="title-shape">
        <svg viewBox="0 0 200 20" xmlns="http://www.w3.org/2000/svg">
          <path d="M 0,10 C 40,0 60,20 100,10 C 140,0 160,20 200,10" fill="none" stroke="currentColor" stroke-width="2">
          </path>
        </svg>
      </div>
      <p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur vel
        illum qui dolorem</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="testimonials-slider swiper init-swiper">
        <script type="application/json" class="swiper-config">
            {
              "slidesPerView": 1,
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "navigation": {
                "nextEl": ".swiper-button-next",
                "prevEl": ".swiper-button-prev"
              }
            }
          </script>

        <div class="swiper-wrapper">

          <div class="swiper-slide">
            <div class="testimonial-item">
              <div class="row">
                <div class="col-lg-8">
                  <h2>Sed ut perspiciatis unde omnis</h2>
                  <p>
                    Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus.
                    Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.
                  </p>
                  <p>
                    Beatae magnam dolore quia ipsum. Voluptatem totam et qui dolore dignissimos. Amet quia sapiente
                    laudantium nihil illo et assumenda sit cupiditate. Nam perspiciatis perferendis minus consequatur.
                    Enim ut eos quo.
                  </p>
                  <div class="profile d-flex align-items-center">
                    <img src="assets/img/person/person-m-7.webp" class="profile-img" alt="">
                    <div class="profile-info">
                      <h3>Saul Goodman</h3>
                      <span>Client</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                  <div class="featured-img-wrapper">
                    <img src="assets/img/person/person-m-7.webp" class="featured-img" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Testimonial Item -->

          <div class="swiper-slide">
            <div class="testimonial-item">
              <div class="row">
                <div class="col-lg-8">
                  <h2>Nemo enim ipsam voluptatem</h2>
                  <p>
                    Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram
                    malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.
                  </p>
                  <p>
                    Dolorem excepturi esse qui amet maxime quibusdam aut repellendus voluptatum. Corrupti enim a
                    repellat cumque est laborum fuga consequuntur. Dolorem nostrum deleniti quas voluptatem iure
                    dolorum rerum. Repudiandae doloribus ut repellat harum vero aut. Modi aut velit aperiam aspernatur
                    odit ut vitae.
                  </p>
                  <div class="profile d-flex align-items-center">
                    <img src="assets/img/person/person-f-8.webp" class="profile-img" alt="">
                    <div class="profile-info">
                      <h3>Sara Wilsson</h3>
                      <span>Designer</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                  <div class="featured-img-wrapper">
                    <img src="assets/img/person/person-f-8.webp" class="featured-img" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Testimonial Item -->

          <div class="swiper-slide">
            <div class="testimonial-item">
              <div class="row">
                <div class="col-lg-8">
                  <h2>
                    Labore nostrum eos impedit
                  </h2>
                  <p>
                    Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit
                    minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.
                  </p>
                  <p>
                    Itaque ut explicabo vero occaecati est quam rerum sed. Numquam tempora aut aut quaerat quia illum.
                    Nobis quia autem odit ipsam numquam. Doloribus sit sint corporis eius totam fuga. Hic nostrum
                    suscipit corrupti nam expedita adipisci aut optio.
                  </p>
                  <div class="profile d-flex align-items-center">
                    <img src="assets/img/person/person-m-9.webp" class="profile-img" alt="">
                    <div class="profile-info">
                      <h3>Matt Brandon</h3>
                      <span>Freelancer</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                  <div class="featured-img-wrapper">
                    <img src="assets/img/person/person-m-9.webp" class="featured-img" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Testimonial Item -->

          <div class="swiper-slide">
            <div class="testimonial-item">
              <div class="row">
                <div class="col-lg-8">
                  <h2>Impedit dolor facilis nulla</h2>
                  <p>
                    Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim
                    tempor labore quem eram duis noster aute amet eram fore quis sint minim.
                  </p>
                  <p>
                    Omnis aspernatur accusantium qui delectus praesentium repellendus. Facilis sint odio aspernatur
                    voluptas commodi qui qui qui pariatur. Corrupti deleniti itaque quaerat ipsum deleniti culpa
                    tempora tempore. Et consequatur exercitationem hic aspernatur nobis est voluptatibus architecto
                    laborum.
                  </p>
                  <div class="profile d-flex align-items-center">
                    <img src="assets/img/person/person-f-10.webp" class="profile-img" alt="">
                    <div class="profile-info">
                      <h3>Jena Karlis</h3>
                      <span>Store Owner</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                  <div class="featured-img-wrapper">
                    <img src="assets/img/person/person-f-10.webp" class="featured-img" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Testimonial Item -->

        </div>

        <div class="swiper-navigation w-100 d-flex align-items-center justify-content-center">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>

      </div>

    </div>

  </section><!-- /Testimonials Section -->

  <!-- Services Section -->
  <section id="services" class="services section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Services</h2>
      <div class="title-shape">
        <svg viewBox="0 0 200 20" xmlns="http://www.w3.org/2000/svg">
          <path d="M 0,10 C 40,0 60,20 100,10 C 140,0 160,20 200,10" fill="none" stroke="currentColor" stroke-width="2">
          </path>
        </svg>
      </div>
      <p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur vel
        illum qui dolorem</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row align-items-center">
        <div class="col-lg-4 mb-5 mb-lg-0">
          <h2 class="fw-bold mb-4 servies-title">Consectetur adipiscing elit sed do eiusmod tempor</h2>
          <p class="mb-4">Nulla metus metus ullamcorper vel tincidunt sed euismod nibh volutpat velit class aptent
            taciti sociosqu ad litora.</p>
          <a href="#" class="btn btn-outline-primary">See all services</a>
        </div>
        <div class="col-lg-8">
          <div class="row g-4">

            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
              <div class="service-item">
                <i class="bi bi-activity icon"></i>
                <h3><a href="service-details.html">Eget nulla facilisi etiam</a></h3>
                <p>Vestibulum morbi blandit cursus risus at ultrices mi tempus imperdiet nulla.</p>
              </div>
            </div><!-- End Service Item -->

            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
              <div class="service-item">
                <i class="bi bi-easel icon"></i>
                <h3><a href="service-details.html">Duis aute irure dolor</a></h3>
                <p>Auctor neque vitae tempus quam pellentesque nec nam aliquam sem et tortor.</p>
              </div>
            </div><!-- End Service Item -->

            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
              <div class="service-item">
                <i class="bi bi-broadcast icon"></i>
                <h3><a href="service-details.html">Excepteur sint occaecat</a></h3>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium.</p>
              </div>
            </div><!-- End Service Item -->

            <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
              <div class="service-item">
                <i class="bi bi-bounding-box-circles icon"></i>
                <h3><a href="service-details.html">Tempor incididunt ut labore</a></h3>
                <p>Ullamco laboris nisi ut aliquip ex ea commodo consequat duis aute irure dolor.</p>
              </div>
            </div><!-- End Service Item -->

          </div>
        </div>
      </div>

    </div>

  </section><!-- /Services Section -->

  <!-- Faq Section -->
  <section id="faq" class="faq section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Frequently Asked Questions</h2>
      <div class="title-shape">
        <svg viewBox="0 0 200 20" xmlns="http://www.w3.org/2000/svg">
          <path d="M 0,10 C 40,0 60,20 100,10 C 140,0 160,20 200,10" fill="none" stroke="currentColor" stroke-width="2">
          </path>
        </svg>
      </div>
      <p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur vel
        illum qui dolorem</p>
    </div><!-- End Section Title -->

    <div class="container">

      <div class="row justify-content-center">

        <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

          <div class="faq-container">

            <div class="faq-item faq-active">
              <h3>Non consectetur a erat nam at lectus urna duis?</h3>
              <div class="faq-content">
                <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur
                  gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

            <div class="faq-item">
              <h3>Feugiat scelerisque varius morbi enim nunc faucibus?</h3>
              <div class="faq-content">
                <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet
                  id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque
                  elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

            <div class="faq-item">
              <h3>Dolor sit amet consectetur adipiscing elit pellentesque?</h3>
              <div class="faq-content">
                <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar
                  elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque
                  eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis
                  sed odio morbi quis</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

            <div class="faq-item">
              <h3>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</h3>
              <div class="faq-content">
                <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet
                  id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque
                  elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

            <div class="faq-item">
              <h3>Tempus quam pellentesque nec nam aliquam sem et tortor?</h3>
              <div class="faq-content">
                <p>Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in.
                  Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est.
                  Purus gravida quis blandit turpis cursus in</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

            <div class="faq-item">
              <h3>Perspiciatis quod quo quos nulla quo illum ullam?</h3>
              <div class="faq-content">
                <p>Enim ea facilis quaerat voluptas quidem et dolorem. Quis et consequatur non sed in suscipit sequi.
                  Distinctio ipsam dolore et.</p>
              </div>
              <i class="faq-toggle bi bi-chevron-right"></i>
            </div><!-- End Faq item-->

          </div>

        </div><!-- End Faq Column-->

      </div>

    </div>

  </section><!-- /Faq Section -->

  <!-- Contact Section -->
  <section id="contact" class="contact section light-background">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-5">
        <div class="col-lg-6">
          <div class="content" data-aos="fade-up" data-aos-delay="200">
            <div class="section-category mb-3">Contact</div>
            <h2 class="display-5 mb-4">Nemo enim ipsam voluptatem quia voluptas aspernatur</h2>
            <p class="lead mb-4">Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit
              laboriosam.</p>

            <div class="contact-info mt-5">
              <div class="info-item d-flex mb-3">
                <i class="bi bi-envelope-at me-3"></i>
                <span>info@example.com</span>
              </div>

              <div class="info-item d-flex mb-3">
                <i class="bi bi-telephone me-3"></i>
                <span>+1 5589 55488 558</span>
              </div>

              <div class="info-item d-flex mb-4">
                <i class="bi bi-geo-alt me-3"></i>
                <span>A108 Adam Street, New York, NY 535022</span>
              </div>

              <a href="#" class="map-link d-inline-flex align-items-center">
                Open Map
                <i class="bi bi-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="contact-form card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body p-4 p-lg-5">

              <form action="forms/contact.php" method="post" class="php-email-form">
                <div class="row gy-4">

                  <div class="col-12">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                  </div>

                  <div class="col-12 ">
                    <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                  </div>

                  <div class="col-12">
                    <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                  </div>

                  <div class="col-12">
                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                  </div>

                  <div class="col-12 text-center">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your message has been sent. Thank you!</div>

                    <button type="submit" class="btn btn-submit w-100">Submit Message</button>
                  </div>

                </div>
              </form>

            </div>
          </div>
        </div>

      </div>

    </div>

  </section><!-- /Contact Section -->

</main>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
    class="bi bi-chevron-double-up"></i></a>

<?php include(__DIR__ . '/user/includes/footer.php'); ?>

</body>

</html>