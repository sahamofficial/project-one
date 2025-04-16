<?php
session_start();
require_once __DIR__ . '/../../config.php';
error_reporting(0);

if (strlen($_SESSION['alogin']) == 0) {
  header('location:../../index.php');
} else {
  if (isset($_POST['change'])) {
    $password = $_POST['password'];
    $newpassword = $_POST['newpassword'];
    $username = $_SESSION['alogin'];

    $sql = "SELECT Password FROM admin WHERE UserName=:username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
      if (password_verify($password, $result->Password)) {
        $hashedNewPassword = password_hash($newpassword, PASSWORD_DEFAULT);

        $update_sql = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
        $update_query = $dbh->prepare($update_sql);
        $update_query->bindParam(':username', $username, PDO::PARAM_STR);
        $update_query->bindParam(':newpassword', $hashedNewPassword, PDO::PARAM_STR);
        $update_query->execute();

        echo "<script>alert('Your password has been successfully changed!'); window.location.href='change-password.php';</script>";
        exit();
      } else {
        echo "<script>alert('Your current password is incorrect!');</script>";
      }
    } else {
      echo "<script>alert('User not found!');</script>";
    }
  }
  ?>

  <head>
    <title>New Royal Flower | Change Password</title>
  </head>
  <script type="text/javascript">
    function valid() {
      if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        alert("New Password and Confirm Password Field do not match  !!");
        document.chngpwd.confirmpassword.focus();
        return false;
      }
      return true;
    }
  </script>

  <body>
    <?php include(__DIR__ . '/../includes/header.php'); ?>

    <div class="content-wrapper">
      <div class="container">
        <div class="row pad-botm">
          <div class="col-md-12">
            <h4 class="header-line">Change Password</h4>
          </div>
        </div>
        <?php if ($error) { ?>
          <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
        <?php } else if ($msg) { ?>
            <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div class="panel panel-info">
              <div class="panel-body">
                <form role="form" method="post" onSubmit="return valid();" name="chngpwd">

                  <div class="form-group">
                    <label>Current Password</label>
                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                  </div>

                  <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control" type="password" name="newpassword" autocomplete="off" required />
                  </div>

                  <div class="form-group">
                    <label>Confirm Password </label>
                    <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                  </div>

                  <button type="submit" name="change" class="btn btn-info">Chnage </button>
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