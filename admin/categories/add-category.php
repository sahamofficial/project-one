<?php
session_start();
error_reporting(0);
require_once __DIR__ . '/../../config.php';
if (strlen($_SESSION['alogin']) == 0) {
    header('location:../auth/login.php');
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

    <head>
        <title>New Royal Flowers | Add Categories</title>
    </head>

    <body>

        <?php include(__DIR__ . '/../includes/header.php'); ?>

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

        <?php include(__DIR__ . '/../includes/footer.php'); ?>
    </body>

    </html>
<?php } ?>