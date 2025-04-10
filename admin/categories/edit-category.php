<?php
session_start();
error_reporting(0);
require_once __DIR__ . '/../../config.php';
if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
} else {

    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $catid = intval($_GET['catid']);
        $sql = "update  categories set name=:name,description=:description where id=:catid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':catid', $catid, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg'] = "Category updated successfully";
        header('location:manage-categories.php');


    }
    ?>

    <head>
        <title>New Royal Flowers | Edit Categories</title>
    </head>

    <body>

        <?php include(__DIR__ . '/../includes/header.php'); ?>


        <div class=" content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Edit category</h4>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"">
<div class=" panel panel-info">
                        <div class="panel-heading">
                            Category Info
                        </div>

                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php
                                $catid = intval($_GET['catid']);
                                $sql = "SELECT * from categories where id=:catid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':catid', $catid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                        ?>
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <input class="form-control" type="text" name="name"
                                                value="<?php echo htmlentities($result->name); ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>

                                            <input class="form-control" type="text" name="description"
                                                value="<?php echo htmlentities($result->description); ?>" />

                                        <?php }
                                } ?>
                                    <button type="submit" name="update" class="btn btn-info">Update </button>

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