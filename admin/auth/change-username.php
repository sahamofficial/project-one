<?php
session_start();
require_once __DIR__ . '/../../config.php';
error_reporting(0);

if (strlen($_SESSION['alogin']) == 0) {
  header('location:../../index.php');
} else {
  if (isset($_POST['change'])) {
    $password = $_POST['password'];
    $newUsername = $_POST['newusername'];
    $currentUsername = $_SESSION['alogin'];

    $sql = "SELECT Password FROM admin WHERE UserName=:currentUsername";
    $query = $dbh->prepare($sql);
    $query->bindParam(':currentUsername', $currentUsername, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
      if (password_verify($password, $result->Password)) {
        $check_sql = "SELECT UserName FROM admin WHERE UserName=:newUsername";
        $check_query = $dbh->prepare($check_sql);
        $check_query->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
        $check_query->execute();

        if ($check_query->rowCount() > 0) {
          echo "<script>alert('This username is already taken! Try another.');</script>";
        } else {
          $update_sql = "UPDATE admin SET UserName=:newUsername WHERE UserName=:currentUsername";
          $update_query = $dbh->prepare($update_sql);
          $update_query->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
          $update_query->bindParam(':currentUsername', $currentUsername, PDO::PARAM_STR);
          $update_query->execute();

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

<head>
  <title>New Royal Flower | Change Username</title>
</head>

<body>
<?php include(__DIR__ . '/../includes/header.php'); ?>
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

  <?php include(__DIR__ . '/../includes/footer.php'); ?>

</body>

</html>