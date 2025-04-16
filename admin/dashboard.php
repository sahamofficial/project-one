<?php
session_start();
error_reporting(0);
include('../config.php');

if (strlen($_SESSION['alogin']) == 0) {
  header('location:auth/login.php');
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    .back-widget-set {
      transition: transform 0.2s ease-in-out;
      cursor: pointer;
    }
    .back-widget-set:hover {
      transform: scale(1.05);
    }
  </style>
</head>

<body>
  <?php include(__DIR__ . '/includes/header.php'); ?>

  <div class="content-wrapper">
    <div class="container">
      <div class="row pad-botm">
        <div class="col-md-12">
          <h4 class="header-line">ADMIN DASHBOARD</h4>
        </div>
      </div>

      <div class="row">

        <!-- Categories Summary -->
        <a href="categories/manage-categories.php" class="col-md-3 col-sm-3 col-xs-6">
          <div class="alert alert-success back-widget-set text-center">
            <i class="fa fa-book fa-5x"></i>
            <?php
            $sql = "SELECT id FROM categories";
            $query = $dbh->prepare($sql);
            $query->execute();
            $listdcategories = $query->rowCount();
            ?>
            <h3><?php echo htmlentities($listdcategories); ?></h3>
            Categories Listed
          </div>
        </a>

        <!-- Products Summary -->
        <a href="manage-products.php" class="col-md-3 col-sm-3 col-xs-6">
          <div class="alert alert-info back-widget-set text-center">
            <i class="fa fa-file-archive fa-5x"></i>
            <?php
            $sql5 = "SELECT id FROM products";
            $query5 = $dbh->prepare($sql5);
            $query5->execute();
            $listdcats = $query5->rowCount();
            ?>
            <h3><?php echo htmlentities($listdcats); ?> </h3>
            Products Listed
          </div>
        </a>

        <?php
        // Order counts
        $totalOrders = $dbh->query("SELECT id FROM orders")->rowCount();
        $pendingOrders = $dbh->query("SELECT id FROM orders WHERE status = 'Pending'")->rowCount();
        $shippedOrders = $dbh->query("SELECT id FROM orders WHERE status = 'Shipped'")->rowCount();
        $deliveredOrders = $dbh->query("SELECT id FROM orders WHERE status = 'Delivered'")->rowCount();
        ?>

        <!-- Total Orders -->
        <a href="orders/manage-orders.php?status=all" class="col-md-3 col-sm-3 col-xs-6">
          <div class="alert alert-warning back-widget-set text-center">
            <i class="fa fa-shopping-cart fa-5x"></i>
            <h3><?php echo $totalOrders; ?></h3>
            Total Orders
          </div>
        </a>

        <!-- Pending Orders -->
        <a href="orders/manage-orders.php?status=Pending" class="col-md-3 col-sm-3 col-xs-6">
          <div class="alert alert-danger back-widget-set text-center">
            <i class="fa fa-hourglass-half fa-5x"></i>
            <h3><?php echo $pendingOrders; ?></h3>
            Pending Orders
          </div>
        </a>

        <!-- Shipped Orders -->
        <a href="orders/manage-orders.php?status=Shipped" class="col-md-3 col-sm-3 col-xs-6">
          <div class="alert alert-info back-widget-set text-center">
            <i class="fa fa-truck fa-5x"></i>
            <h3><?php echo $shippedOrders; ?></h3>
            Shipped Orders
          </div>
        </a>

        <!-- Delivered Orders -->
        <a href="orders/manage-orders.php?status=Delivered" class="col-md-3 col-sm-3 col-xs-6">
          <div class="alert alert-success back-widget-set text-center">
            <i class="fa fa-check fa-5x"></i>
            <h3><?php echo $deliveredOrders; ?></h3>
            Delivered Orders
          </div>
        </a>

      </div>
    </div>
  </div>

  <?php include(__DIR__ . '/includes/footer.php'); ?>
</body>
</html>
