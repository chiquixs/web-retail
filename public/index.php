<?php
ob_start();
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['qty'];
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
        // Clear all buffers before checkout
        while (ob_get_level()) {
            ob_end_clean();
        }
        require_once '../app/controllers/CheckoutController.php';
        $controller = new CheckoutController($pdo);
        $controller->process($_POST);
        exit; 

    case 'login':
        require_once '../app/controllers/AuthController.php'; // 🎯 Controller baru untuk Auth
        $controller = new AuthController($pdo);
        $controller->showLoginForm(); // Panggil fungsi untuk menampilkan form
        break;
    
    case 'process_login':
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->processLogin();
    break;

    case 'get_cart_count':
    require_once '../app/controllers/CartController.php';
    $controller = new CartController($pdo);
    $controller->getCartCount();
    exit;

    case 'checkout_process':
        require_once '../app/controllers/CheckoutController.php';
        $controller = new CheckoutController($pdo);
        // Pastikan Anda memanggil fungsi process()
        $controller->process($_POST); 
        break;

   // ... code sebelumnya ...

    case 'checkout_success':
        // HAPUS baris echo lama:
        // echo "<h1>Terima Kasih! Pesanan Anda berhasil.</h1>...";
        
        // GANTI dengan memanggil view baru:
        require_once '../app/views/cart/thankyou.php';
        break;

    // ... code selanjutnya ...


    default:
        header('Location: index.php?page=home');
        exit;
}
ob_end_flush();
