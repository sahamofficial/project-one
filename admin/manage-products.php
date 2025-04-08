<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:../adminlogin.php');
} else { 

    if(isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "DELETE FROM products WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['delmsg'] = "Product deleted successfully";
        header('location:manage-products.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Shop | Manage Products</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Products</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>Products Listing</span>
                            <a href="add-product.php?id=<?php echo htmlentities($result->id); ?>">
                                    <button class="btn btn-primary"><i class="fa fa-edit "></i>Add</button>
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

                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>                                      
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt);?></td>
                                                    <td class="center"><?php echo htmlentities($result->name);?></td>
                                                    <td class="center"><?php echo htmlentities($result->category);?></td>
                                                    <td class="center">$<?php echo htmlentities($result->price);?></td>
                                                    <td class="center">
                                                        <img src="productImg/<?php echo htmlentities($result->image);?>" width="100">
                                                    </td>
                                                    <td class="center">
                                                        <a href="edit-products.php?id=<?php echo htmlentities($result->id);?>">
                                                            <button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                        <a href="manage-products.php?del=<?php echo htmlentities($result->id);?>" 
                                                           onclick="return confirm('Are you sure you want to delete?');">
                                                            <button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php 
                                                $cnt++;
                                            } 
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

    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>
