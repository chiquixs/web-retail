<?php
require_once '../app/models/CartModel.php';

class CartController {
    private $model;

    public function __construct($db) {
        $this->model = new CartModel($db);
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Aksi 1: Menampilkan halaman keranjang
    public function index() {
        $cart = $_SESSION['cart'];
        $productIds = array_keys($cart);

        $products = $this->model->getCartDetails($productIds);
        
        $cartItems = [];
        $totalAmount = 0;

        foreach ($products as $product) {
            $id = $product['id_product'];
            $qty = $cart[$id]['qty'];
            $subtotal = $qty * $product['price'];
            $totalAmount += $subtotal;

            $cartItems[] = [
                'id_product' => $id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'qty' => $qty,
                'subtotal' => $subtotal,
            ];
        }

        $data = [
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
        ];

        require_once '../app/views/cart/index.php';
    }

    // Aksi 2: Menambah item ke keranjang
    public function add($request) {
        // CRITICAL: Set header JSON PERTAMA KALI
        header('Content-Type: application/json');
        
        // Disable error output yang bisa merusak JSON
        ini_set('display_errors', 0);
        
        try {
            // Log untuk debugging
            error_log("=== ADD TO CART ===");
            error_log("REQUEST METHOD: " . $_SERVER['REQUEST_METHOD']);
            error_log("POST DATA: " . print_r($_POST, true));
            
            $is_post = ($_SERVER['REQUEST_METHOD'] === 'POST');
            $source_data = $is_post ? $_POST : $request; 
            
            // Ambil data
            $id_product = (int)($source_data['id'] ?? $source_data['id_product'] ?? 0);
            $name = trim($source_data['name'] ?? 'Unknown Product');
            $price = (float)($source_data['price'] ?? 0);
            $qty = (int)($source_data['qty'] ?? 1);

            error_log("PARSED DATA - ID: $id_product, Name: $name, Price: $price, Qty: $qty");

            // Validasi
            if ($id_product <= 0) {
                throw new Exception('Product ID is required');
            }
            
            if ($price <= 0) {
                throw new Exception('Invalid price');
            }
            
            if ($qty <= 0) {
                throw new Exception('Invalid quantity');
            }

            // Pastikan session cart ada
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Tambah atau update
            if (isset($_SESSION['cart'][$id_product])) {
                $_SESSION['cart'][$id_product]['qty'] += $qty;
            } else {
                $_SESSION['cart'][$id_product] = [
                    'id_product' => $id_product,
                    'name' => $name,
                    'price' => $price,
                    'qty' => $qty
                ];
            }
            
            // Hitung cart count
            $cart_count = 0;
            foreach ($_SESSION['cart'] as $item) {
                $cart_count += $item['qty'];
            }
            
            error_log("CART COUNT: $cart_count");
            error_log("SESSION CART: " . print_r($_SESSION['cart'], true));
            
            // Return JSON success
            echo json_encode([
                'success' => true,
                'cart_count' => $cart_count,
                'message' => 'Product added to cart successfully'
            ]);
            
        } catch (Exception $e) {
            error_log("ADD TO CART ERROR: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Aksi 3: Update kuantitas (AJAX)
    public function update($request) {
        header('Content-Type: application/json'); 
        ini_set('display_errors', 0);

        try {
            $id = isset($request['id_product']) ? (int)$request['id_product'] : 0;
            $qty = isset($request['qty']) ? (int)$request['qty'] : 0;

            if ($id <= 0 || $qty <= 0) {
                throw new Exception('Invalid product ID or quantity.');
            }

            if (!isset($_SESSION['cart'][$id])) {
                throw new Exception('Product not found in cart.');
            }
            
            $_SESSION['cart'][$id]['qty'] = $qty;
            
            $cart_count = 0;
            $grand_total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $cart_count += $item['qty'];
                $grand_total += ($item['price'] * $item['qty']);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'cart_count' => $cart_count,
                'grand_total' => $grand_total,
                'item_subtotal' => $_SESSION['cart'][$id]['price'] * $qty
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    // Aksi 4: Hapus item
    public function remove($request) {
        $id = $request['id'] ?? null;
        
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        
        header('Location: index.php?page=cart');
        exit;
    }

   // Method untuk mengambil cart count (AJAX)
// PERBAIKAN: Ganti method getCartCount() di CartController
public function getCartCount() {
    header('Content-Type: application/json');
    ini_set('display_errors', 0); // Disable error display
    
    $cartCount = 0; // ✅ Konsisten dengan nama variabel
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['qty']; // ✅ Perbaiki dari $cart_count ke $cartCount
        }
    }
    
    echo json_encode([
        'success' => true,
        'cart_count' => $cartCount
    ]);
    exit;
}
}
?>  