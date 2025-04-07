<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
  header('location:../adminlogin.php');
} else { ?>
  <!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

  </head>

  <body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
      <div class="container">
        <div class="row pad-botm">
          <div class="col-md-12">
            <h4 class="header-line">ADMIN DASHBOARD</h4>

          </div>

        </div>

        <div class="row">
          <a href="manage-categories.php">
            <div class="col-md-3 col-sm-3 col-xs-6">
              <div class="alert alert-success back-widget-set text-center">
                <i class="fa fa-book fa-5x"></i>
                <?php
                $sql = "SELECT id from categories ";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $listdcategories = $query->rowCount();
                ?>
                <h3><?php echo htmlentities($listdcategories); ?></h3>
                Categories Listed
              </div>
            </div>
          </a>

          <div class="row">
            <a href="manage-categories.php">
              <div class="col-md-3 col-sm-3 rscol-xs-6">
                <div class="alert alert-info back-widget-set text-center">
                  <i class="fa fa-file-archive-o fa-5x"></i>
                  <?php
                  $sql5 = "SELECT id from products ";
                  $query5 = $dbh->prepare($sql5);
                  $query5->execute();
                  $results5 = $query5->fetchAll(PDO::FETCH_OBJ);
                  $listdcats = $query5->rowCount();
                  ?>

                  <h3><?php echo htmlentities($listdcats); ?> </h3>
                  Products Listed
                </div>
              </div>
            </a>
          </div>

        </div>
      </div>

      <?php include('includes/footer.php'); ?>
  </body>

  </html>
<?php } ?>