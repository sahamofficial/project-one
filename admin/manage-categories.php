<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
} else {
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "delete from categories  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['delmsg'] = "Category deleted scuccessfully ";
        header('location:manage-categories.php');

    }


    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>New Royal Flowers | Manage Categories</title>

    </head>

    <body>

        <?php include('includes/header.php'); ?>

        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Manage Categories</h4>
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
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <Span>Categories Listing</Span>
                                <a href="add-category.php?catid=<?php echo htmlentities($result->id); ?>">
                                    <button class="btn btn-primary"><i class="fa fa-edit "></i>Add</button>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sql = "SELECT * from  categories";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <tr class="odd gradeX">

                                                        <td class="center"><?php echo htmlentities($cnt); ?></td>

                                                        <td class="center"><?php echo htmlentities($result->name); ?></td>

                                                        <td class="center"><?php echo htmlentities($result->description); ?></td>

                                                        <td class="center">

                                                            <a
                                                                href="edit-category.php?catid=<?php echo htmlentities($result->id); ?>"><button
                                                                    class="btn btn-primary"><i class="fa fa-edit "></i>
                                                                    Edit</button>
                                                                <a href="javascript:void(0);" class="btn btn-danger delete-btn"
                                                                    data-id="<?php echo htmlentities($result->id); ?>">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </a>

                                                        </td>
                                                    </tr>
                                                    <?php $cnt = $cnt + 1;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!--End Advanced Tables -->
                    </div>
                </div>



            </div>
        </div>

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
    </body>

    </html>
<?php } ?>