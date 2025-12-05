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
    // ===== PUBLIC PAGES =====
    
    case 'home':
        require_once '../app/controllers/HomeController.php';
        $controller = new HomeController($pdo);
        $controller->index();
        break;

    case 'shop':
        require_once '../app/controllers/ProductController.php';
        $controller = new ProductController($pdo);
        $controller->index();
        break;

    // ===== CART OPERATIONS =====
    
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

    case 'get_cart_count':
        require_once '../app/controllers/CartController.php';
        $controller = new CartController($pdo);
        $controller->getCartCount();
        exit;

    // ===== CHECKOUT =====
    
    case 'checkout_process':
        // Clear all buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        require_once '../app/controllers/CheckoutController.php';
        $controller = new CheckoutController($pdo);
        $controller->process($_POST);
        exit; // ✅ CRITICAL: Must have exit!

    case 'checkout_success':
        require_once '../app/views/cart/thankyou.php';
        break;

    // ===== AUTHENTICATION =====
    
    case 'login':
        require_once '../app/controllers/admin/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->showLoginForm();
        break;

    case 'auth_login':
        require_once '../app/controllers/admin/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->login($_POST);
        break;

    case 'logout':
        require_once '../app/controllers/admin/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->logout();
        break;

    // ===== ADMIN DASHBOARD =====
    
    case 'admin_dashboard':
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=login");
            exit;
        }
        require_once '../app/controllers/admin/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->index();
        break;
        
    case 'admin_daily_sales':
            if (empty($_SESSION['admin_logged_in'])) {
                header("Location: index.php?page=login");
                exit;
            }
            require_once '../app/controllers/admin/DashboardController.php';
            $controller = new DashboardController($pdo);
            $controller->getDailySalesData();
        break;

    case 'admin_refresh_mv_daily_sales_summary':
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=login");
            exit;
        }
        require_once '../app/controllers/admin/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->refreshDailySalesMV();
        break;

    case 'admin_best_selling_products':
            if (empty($_SESSION['admin_logged_in'])) {
                header("Location: index.php?page=login");
                exit;
            }
            require_once '../app/controllers/admin/DashboardController.php';
            $controller = new DashboardController($pdo);
            $controller->getBestSellingProducts();
        break;

    case 'admin_refresh_mv_best_selling_products':
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=login");
            exit;
        }
        require_once '../app/controllers/admin/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->refreshBestSellingProductsMV();
        break;

    // ===== ADMIN PRODUCT MANAGEMENT =====
    
    case 'admin_product':
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=login");
            exit;
        }

        require_once '../app/controllers/cms/cmsProductController.php';
        $controller = new ProductController($pdo);

        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'add':
                $controller->add($_POST);
                break;
            case 'update':
                $controller->update($_POST);
                break;
            case 'delete':
                $controller->delete($_POST);
                break;
            case 'get_categories':
                $controller->getCategories();
                break;
            case 'get_suppliers':
                $controller->getSuppliers();
                break;
            default:
                $controller->index();
        }
        break;

    // ===== ADMIN CATEGORY MANAGEMENT =====
    
    case 'admin_category':
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=login");
            exit;
        }

        require_once '../app/controllers/cms/CategoryController.php';
        $controller = new CategoryController($pdo);

        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'add':
                $controller->add($_POST);
                break;
            case 'update':
                $controller->update($_POST);
                break;
            case 'delete':
                $controller->delete($_POST);
                break;
            default:
                $controller->index();
        }
        break;

    // ===== ADMIN SUPPLIER MANAGEMENT =====
    
    case 'admin_supplier':
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=login");
            exit;
        }

        require_once '../app/controllers/cms/SupplierController.php';
        $controller = new SupplierController($pdo);

        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'add':
                $controller->add($_POST);
                break;
            case 'update':
                $controller->update($_POST);
                break;
            case 'delete':
                $controller->delete($_POST);
                break;
            default:
                $controller->index();
        }
        break;

    // ===== ADMIN CUSTOMER MANAGEMENT =====
    
    case 'admin_customer':
        if (empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=login");
            exit;
        }

        require_once '../app/controllers/cms/CustomerController.php';
        $controller = new CustomerController($pdo);

        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'add':
                $controller->add($_POST);
                break;
            case 'update':
                $controller->update($_POST);
                break;
            case 'delete':
                $controller->delete($_POST);
                break;
            default:
                $controller->index();
        }
        break;

    // ===== DEFAULT =====
    
    default:
        header('Location: index.php?page=home');
        exit;
}

ob_end_flush();
?>