<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Royal Flower | Admin Dash Board</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/main.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div
            class="header-container container-fluid container-xl position-relative d-flex flex-column flex-md-row align-items-center justify-content-between">
            <a href="#" class="logo d-flex flex-column flex-md-row align-items-center me-auto me-xl-0">
                <img src="assets/img/Icon.png" alt="">
                <h1 class="sitename">New Royal Flowers</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul class="d-flex flex-row">
                    <li>
                        <a href="dashboard.php" class="active">
                            <i class="bi bi-house navicon"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="active">
                            <i class="bi bi-house navicon"></i>
                            <span class="nav-text">Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="manage-categories.php" class="active">
                            <i class="bi bi-house navicon"></i>
                            <span class="nav-text">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="manage-products.php">
                            <i class="bi bi-cart4 navicon"></i>
                            <span class="nav-text">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-person-lines-fill navicon"></i>
                            <span class="nav-text">Me</span>
                        </a>
                    </li>
                    <div class="right-div">
                        <a href="logout.php" class="btn btn-danger pull-right">LOG ME OUT</a>
                    </div>
                </ul>
            </nav>
        </div>
    </header>

    <!-- LOGO HEADER END-->
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="dashboard.php" class="menu-top-active">DASHBOARD</a></li>

                            <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Categories
                                    <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-category.php">Add
                                            Category</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                            href="manage-categories.php">Manage Categories</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Products <i
                                        class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-product.php">Add
                                            Product</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                            href="manage-products.php">Manage
                                            Products</a></li>
                                </ul>
                            </li>

                            <li><a href="change-password.php">Change Password</a></li>
                            <li><a href="change-username.php">Change Username</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>