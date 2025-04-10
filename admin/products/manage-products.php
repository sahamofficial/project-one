<?php
session_start();
error_reporting(0);
require_once __DIR__ . '/../../config.php';

if (strlen($_SESSION['alogin']) == 0) {
    header('location:../auth/login.php');
    exit;
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "DELETE FROM products WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $_SESSION['delmsg'] = "Product deleted successfully";
    header('location:manage-products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>New Royal Flowers | Manage Products</title>
</head>

<body>
    <?php include(__DIR__ . '/../includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Products</h4>
                </div>
                <div class="row">
                    <?php
                    $alerts = [
                        'error' => 'danger',
                        'msg' => 'success',
                        'updatemsg' => 'success',
                        'delmsg' => 'success'
                    ];

                    foreach ($alerts as $key => $type) {
                        if (!empty($_SESSION[$key])) {
                            ?>
                            <div class="col-md-6">
                                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
                                    <strong><?php echo ucfirst($type); ?>:</strong>
                                    <?php echo htmlentities($_SESSION[$key]); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                            <?php
                            $_SESSION[$key] = "";
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>Products Listing</span>
                            <a href="add-product.php">
                                <button class="btn btn-primary"><i class="fa fa-edit "></i> Add</button>
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT products.id, products.name, categories.name AS category, products.price, products.image 
                                                FROM products 
                                                LEFT JOIN categories ON categories.id = products.catid";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {
                                                $productId = $result->id ?? 0;
                                                $productName = $result->name ?? '';
                                                $categoryName = $result->category ?? 'Uncategorized';
                                                $price = $result->price ?? 0;
                                                $image = $result->image ?? 'default.jpg';
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($productName); ?></td>
                                                    <td class="center"><?php echo htmlentities($categoryName); ?></td>
                                                    <td class="center">$<?php echo htmlentities(number_format($price, 2)); ?>
                                                    </td>
                                                    <td class="center">
                                                        <img src="../productImg/<?php echo htmlentities($image); ?>" width="100"
                                                            alt="<?php echo htmlentities($productName); ?>">
                                                    </td>
                                                    <td class="center">
                                                        <a href="edit-products.php?id=<?php echo htmlentities($productId); ?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i>
                                                                Edit</button>
                                                        </a>
                                                        <a href="javascript:void(0);" class="btn btn-danger delete-product-btn"
                                                            data-id="<?php echo htmlentities($productId); ?>"
                                                            data-name="<?php echo htmlentities($productName); ?>">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </a>

                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                        } else {
                                            echo '<tr><td colspan="6" class="text-center">No products found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            });
        }, 3000);
    </script>

    <?php include(__DIR__ . '/../includes/footer.php'); ?>

</body>

</html>