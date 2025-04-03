<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
} else {

    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];

        $sql = "INSERT INTO categories(name, description) VALUES(:name, :description)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);

        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $_SESSION['msg'] = "Category Listed successfully";
            header('location:manage-categories.php');
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:manage-categories.php');
        }
    }

    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Library Management System | Add Categories</title>

        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    </head>

    <body>
        <?php include('includes/header.php'); ?>
        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Add category</h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Category Info
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post">

                                    <div class="form-group">
                                        <label>Category Name</label>
                                        <input class="form-control" type="text" name="name" autocomplete="off" required />
                                    </div>
                                    <div class="form-group">
                                        <label>Category Description</label>
                                        <input class="form-control" type="text" name="description" autocomplete="off"
                                            required />
                                    </div>
                                    <button type="submit" name="create" class="btn btn-info">Create </button>

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