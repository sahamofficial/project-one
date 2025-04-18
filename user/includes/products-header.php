<?php
require_once __DIR__ . '/../../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>New Royal Flowers</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link href="<?= url('assets\img\Icon.png') ?>" rel="icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Noto+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Questrial:wght@400&display=swap"
        rel="stylesheet">

    <link href="<?= url('assets\vendor\bootstrap-icons\bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= url('assets\vendor\bootstrap\css\bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= url('assets\vendor\aos\aos.css') ?>" rel="stylesheet">
    <link href="<?= url('assets\vendor\glightbox\css\glightbox.min.css') ?>" rel="stylesheet">
    <link href="<?= url('assets\vendor\swiper\swiper-bundle.min.css') ?>" rel="stylesheet">
    <link href="<?= url('assets\css\main.css') ?>" rel="stylesheet">

    <script>
        const BASE_URL = "<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>";
    </script>

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div
            class="header-container container-fluid container-xl position-relative d-flex flex-column flex-md-row align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex flex-column flex-md-row align-items-center me-auto me-xl-0">
                <img src="<?= url('assets\img\Icon.png') ?>" alt="">
                <h1 class="sitename">New Royal Flowers</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul class="d-flex flex-row">
                    <li><a href="<?= url('index.php') ?>"><i class="bi bi-house navicon"></i><span
                                class="nav-text">Home</span></a>
                    </li>
                    <li><a href="<?= url('all-products.php') ?>"><i class="bi bi-cart4 navicon"></i><span
                                class="nav-text">Products</span></a></li>
                    <li><a href="<?= url('user\cart\cart.php') ?>"><i class="bi bi-cart4 navicon"></i><span
                                class="nav-text">Cart</span></a></li>
                    <li><a href="<?= url('user\orders\order-history.php') ?>"><i class="bi bi-list-ul navicon"></i><span
                                class="nav-text">Placed Orders</span></a></li>
                </ul>
            </nav>
        </div>
    </header>