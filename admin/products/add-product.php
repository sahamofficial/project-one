<?php
session_start();
error_reporting(0);
require_once __DIR__ . '/../../config.php';

if (strlen($_SESSION['alogin']) == 0) {
    header('location:../auth/login.php');
} else {

    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $productimg = $_FILES["productpic"]["name"];

        $extension = substr($productimg, strlen($productimg) - 4, strlen($productimg));
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
        $imgnewname = md5($productimg . time()) . $extension;

        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            move_uploaded_file($_FILES["productpic"]["tmp_name"], "../productImg/" . $imgnewname);
            $sql = "INSERT INTO products (name, catid, price, image) VALUES (:name, :category, :price, :imgnewname)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':category', $category, PDO::PARAM_INT);
            $query->bindParam(':price', $price, PDO::PARAM_STR);
            $query->bindParam(':imgnewname', $imgnewname, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();

            if ($lastInsertId) {
                $_SESSION['msg'] = "Products Listed successfully";
                header('location:manage-products.php');
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:manage-products.php');
            }
        }
    }
    ?>

    <head>
        <title>New Royal Flowers | Add Product</title>
    </head>

    <body>
        <?php include(__DIR__ . '/../includes/header.php'); ?>

        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Add Product</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Product Info
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post" enctype="multipart/form-data">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Name<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="name" autocomplete="off"
                                                required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category<span style="color:red;">*</span></label>
                                            <select class="form-control" name="category" required="required">
                                                <option value=""> Select Category</option>
                                                <?php
                                                $sql = "SELECT * FROM categories";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) { ?>
                                                        <option value="<?php echo htmlentities($result->id); ?>">
                                                            <?php echo htmlentities($result->name); ?>
                                                        </option>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="price" autocomplete="off"
                                                required="required" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Image<span style="color:red;">*</span></label>
                                            <input class="form-control" type="file" name="productpic" autocomplete="off"
                                                required="required" />
                                        </div>
                                    </div>

                                    <button type="submit" name="add" class="btn btn-info">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php include(__DIR__ . '/../includes/footer.php'); ?>
    </body>

    </html>
<?php } ?>