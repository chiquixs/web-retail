<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_count = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    if (!empty($quantities)) {
        $cart_count = array_sum($quantities);
    }
}
require_once '../app/config/database.php';

$dbConnection = new Koneksi();
$pdo = $dbConnection->getKoneksi();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'shop':
        require_once '../app/controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->index();
        break;

    case 'home':
        require_once '../app/controllers/HomeController.php';
        $controller = new HomeController($pdo);
        $controller->index();
        break;
    
    case 'cart':
        require_once '../app/controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->index();
        break;

    case 'add_cart':
        require_once '../app/controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->add($_REQUEST);
        exit;

    case 'update_cart':
        require_once '../app/controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->update($_REQUEST);
        exit;
    
    case 'remove_cart':
        require_once '../app/controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->remove($_GET);
        exit;

    // TAMBAHKAN ROUTING CHECKOUT
    case 'checkout':
        require_once '../app/controllers/CheckoutController.php';
        $controller = new CheckoutController($pdo);
        $controller->process($_POST);
        exit;

    default:
        header('Location: index.php?page=home');
        exit;
}