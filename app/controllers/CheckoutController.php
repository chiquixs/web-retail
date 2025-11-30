<?php
require_once '../app/models/CheckoutModel.php';

class CheckoutController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new CheckoutModel($db);
    }

    public function process($request) {
        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');
        
        // ✅ PERBAIKAN: Aktifkan error display sementara untuk debugging
        // Setelah fix, bisa di-set 0 lagi
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        try {
            // ✅ TAMBAH: Log awal
            error_log("=== CHECKOUT PROCESS START ===");
            error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid Request Method');
            }

            // Simpan cart ke variabel lokal
            $currentCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            
            // ✅ KRITIS: JANGAN COMMENT INI!
            // Validasi cart tidak boleh kosong
            // if (empty($currentCart)) {
            //     error_log("ERROR: Cart is empty!");
            //     throw new Exception('Keranjang belanja kosong.');
            // }
            
            error_log("Cart items: " . count($currentCart));

            $name = trim($request['name'] ?? '');
            $email = trim($request['email'] ?? '');
            $address = trim($request['address'] ?? '');

            if (empty($name) || empty($email) || empty($address)) {
                throw new Exception('Mohon lengkapi semua data.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Format email tidak valid.');
            }

            error_log("Customer: $name, $email");

            // Get atau Create Customer
            $id_customer = $this->model->getOrCreateCustomer($name, $email, $address);
            
            if (!$id_customer) {
                throw new Exception("Gagal mendapatkan ID Customer.");
            }
            
            error_log("Customer ID: $id_customer");

            // Siapkan data cart
            $cartDataForJson = [];
            foreach ($currentCart as $item) {
                // ✅ TAMBAH: Validasi setiap item
                if (!isset($item['id_product']) || !isset($item['qty'])) {
                    error_log("Invalid cart item: " . json_encode($item));
                    continue; // Skip item yang tidak valid
                }
                
                $cartDataForJson[] = [
                    'id_product' => (int)$item['id_product'],
                    'qty'        => (int)$item['qty']
                ];
            }
            
            // ✅ TAMBAH: Validasi cartDataForJson tidak kosong
            if (empty($cartDataForJson)) {
                throw new Exception('Data produk tidak valid.');
            }
            
            error_log("Cart data prepared: " . json_encode($cartDataForJson));

            // Proses transaksi
            $result = $this->model->createTransaction($id_customer, $cartDataForJson);

            if (!$result) {
                throw new Exception('Gagal memproses transaksi ke database.');
            }

            error_log("Transaction successful!");

            // Set flag checkout sukses SEBELUM clear cart
            $_SESSION['checkout_success'] = true;
            $_SESSION['checkout_message'] = 'Transaksi berhasil! Terima kasih atas pembelian Anda.';

            // Clear cart
            unset($_SESSION['cart']);
            $_SESSION['cart'] = [];
            
            error_log("Cart cleared");

            // Clear buffer dan kirim response
            ob_clean();
            
            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil! Terima kasih atas pembelian Anda.',
                'customer_id' => $id_customer,
                'redirect' => 'index.php?page=checkout_success'
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            error_log("=== PDO EXCEPTION ===");
            error_log("Error: " . $e->getMessage());
            
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("=== GENERAL EXCEPTION ===");
            error_log("Error: " . $e->getMessage());
            
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        ob_end_flush();
        exit;
    }
}