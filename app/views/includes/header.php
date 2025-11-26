<?php

// 1. Definisikan $cart_Count
$cart_count = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Ambil semua nilai 'qty' dari array item cart
    $quantities = array_column($_SESSION['cart'], 'qty');
    
    // Pastikan array_column tidak mengembalikan array kosong sebelum sum
    if (!empty($quantities)) {
        $cart_count = array_sum($quantities); // Jumlahkan semua kuantitas
    }
}

// 2. Cek URL minta halaman apa? (Default: home)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>


<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="assets/css/tiny-slider.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/shop.css" rel="stylesheet">

</head>

<body>
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=home">Blace<span>.</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsFurni">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php?page=home">Home</a>
                    </li>
                    <li><a class="nav-link" href="index.php?page=shop">Shop</a></li>
                    <li><a class="nav-link" href="index.php?page=about">About us</a></li>
                </ul>

                
                 <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li><a class="nav-link" href="#"><img src="assets/images/user.svg"></a></li>
                    <li>
                        <a class="nav-link position-relative" href="index.php?page=cart">
                            <img src="assets/images/cart.svg">
                            <?php 
                            if ($cart_count > 0): ?>
                            <span id="cart-count" class="cart-badge"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>