<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['alogin']) == 0) {
  header('location:index.php');
} else {
  if (isset($_POST['change'])) {
    $password = $_POST['password'];
    $newUsername = $_POST['newusername'];
    $currentUsername = $_SESSION['alogin'];

    // Fetch stored hashed password
    $sql = "SELECT Password FROM admin WHERE UserName=:currentUsername";
    $query = $dbh->prepare($sql);
    $query->bindParam(':currentUsername', $currentUsername, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
      // Verify current password
      if (password_verify($password, $result->Password)) {
        // Check if the new username already exists
        $check_sql = "SELECT UserName FROM admin WHERE UserName=:newUsername";
        $check_query = $dbh->prepare($check_sql);
        $check_query->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
        $check_query->execute();

        if ($check_query->rowCount() > 0) {
          echo "<script>alert('This username is already taken! Try another.');</script>";
        } else {
          // Update username
          $update_sql = "UPDATE admin SET UserName=:newUsername WHERE UserName=:currentUsername";
          $update_query = $dbh->prepare($update_sql);
          $update_query->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
          $update_query->bindParam(':currentUsername', $currentUsername, PDO::PARAM_STR);
          $update_query->execute();

          // Update session with new username
          $_SESSION['alogin'] = $newUsername;

          echo "<script>alert('Your username has been successfully changed!'); window.location.href='change-username.php';</script>";
          exit();
        }
      } else {
        echo "<script>alert('Your current password is incorrect!');</script>";
      }
    } else {
      echo "<script>alert('User not found!');</script>";
    }
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
  <title>New Royal Flower | Change Username</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <link href="assets/css/font-awesome.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
  <style>
    .errorWrap {
      padding: 10px;
      margin: 0 0 20px 0;
      background: #fff;
      border-left: 4px solid #dd3d36;
      box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }
    .succWrap {
      padding: 10px;
      margin: 0 0 20px 0;
      background: #fff;
      border-left: 4px solid #5cb85c;
      box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }
  </style>
</head>
<body>
  <?php include('includes/header.php'); ?>
  <div class="content-wrapper">
    <div class="container">
      <div class="row pad-botm">
        <div class="col-md-12">
          <h4 class="header-line">Change Username</h4>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <div class="panel panel-info">
            <div class="panel-heading">
              Change Username
            </div>
            <div class="panel-body">
              <form role="form" method="post">

                <div class="form-group">
                  <label>Current Password</label>
                  <input class="form-control" type="password" name="password" autocomplete="off" required />
                </div>

                <div class="form-group">
                  <label>New Username</label>
                  <input class="form-control" type="text" name="newusername" autocomplete="off" required />
                </div>

                <button type="submit" name="change" class="btn btn-info">Change Username</button>
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
