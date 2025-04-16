<?php
require_once __DIR__ . '/../../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="<?= url('assets\img\Icon.png') ?>" rel="icon">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Royal Flower | Admin Dash Board</title>
    <link href="<?= url('assets\vendor\bootstrap\css\bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= url('assets\css\admin.css') ?>" rel="stylesheet" />
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
                        <a href="<?= url('admin\dashboard.php') ?>" class="active">
                            <i class="bi bi-house navicon"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('admin\orders\manage-orders.php') ?>" class="active">
                            <i class="bi bi-house navicon"></i>
                            <span class="nav-text">Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('admin\categories\manage-categories.php') ?>" class="active">
                            <i class="bi bi-house navicon"></i>
                            <span class="nav-text">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('admin\products\manage-products.php') ?>">
                            <i class="bi bi-cart4 navicon"></i>
                            <span class="nav-text">Products</span>
                        </a>
                    </li>
                    <li class="dropdown" tabindex="-1">
                        <a href="#" class="dropdown-toggle">
                            <i class="bi bi-person-lines-fill navicon"></i>
                            <span class="nav-text">Me <i class="bi bi-chevron-down"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= url('admin/auth/change-username.php') ?>" class="dropdown-item">
                                    <i class="bi bi-person-circle"></i> Change Username
                                </a>
                            </li>
                            <li>
                                <a href="<?= url('admin/auth/change-password.php') ?>" class="dropdown-item">
                                    <i class="bi bi-lock"></i> Change Password
                                </a>
                            </li>
                        </ul>
                    </li>

                    <div class="right-div">
                        <a href="<?= url('admin\auth\logout.php') ?>" class="btn btn-danger pull-right">Logout</a>
                    </div>
                </ul>
            </nav>
        </div>
    </header>

    <script src="<?= url('assets/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= url('assets/js/admin.js') ?>"></script>
    <script src="<?= url('assets/js/sweetalert.js') ?>"></script>

</body>

</html>