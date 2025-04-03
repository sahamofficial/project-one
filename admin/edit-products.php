<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
} else {
    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $productid = intval($_GET['id']);

        $sql = "UPDATE products SET name=:name, catid=:category, price=:price WHERE id=:productid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':productid', $productid, PDO::PARAM_STR);
        $query->execute();

        echo "<script>alert('Product updated successfully');</script>";
        echo "<script>window.location.href='manage-products.php'</script>";
    }
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Shop | Edit Product</title>
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet" />
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Edit Product</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Product Info
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post">
                                    <?php
                                    $productid = intval($_GET['id']);
                                    $sql = "SELECT products.name, categories.name AS category, categories.id AS catid, products.price, products.image 
                                        FROM products 
                                        LEFT JOIN categories ON categories.id = products.catid 
                                        WHERE products.id=:productid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':productid', $productid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) { ?>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Product Image</label><br>
                                                    <img src="productimg/<?php echo htmlentities($result->image); ?>" width="100">
                                                    <a href="change-productimg.php?id=<?php echo htmlentities($productid); ?>">Change
                                                        Image</a>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Product Name<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="name"
                                                        value="<?php echo htmlentities($result->name); ?>" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Category<span style="color:red;">*</span></label>
                                                    <select class="form-control" name="category" required>
                                                        <option value="<?php echo htmlentities($result->catid); ?>">
                                                            <?php echo htmlentities($result->category); ?>
                                                        </option>
                                                        <?php
                                                        $sql1 = "SELECT * FROM categories";
                                                        $query1 = $dbh->prepare($sql1);
                                                        $query1->execute();
                                                        $resultss = $query1->fetchAll(PDO::FETCH_OBJ);

                                                        if ($query1->rowCount() > 0) {
                                                            foreach ($resultss as $row) {
                                                                if ($result->category != $row->name) { ?>
                                                                    <option value="<?php echo htmlentities($row->id); ?>">
                                                                        <?php echo htmlentities($row->name); ?>
                                                                    </option>
                                                                <?php }
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Price (USD)<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="price"
                                                        value="<?php echo htmlentities($result->price); ?>" required />
                                                </div>
                                            </div>

                                        <?php }
                                    } ?>
                                    <div class="col-md-12">
                                        <button type="submit" name="update" class="btn btn-info">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php include('includes/footer.php'); ?>
        <script src="assets/js/jquery-1.10.2.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>