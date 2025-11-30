<?php
require_once '../app/models/CheckoutModel.php';

class CheckoutController {
    private $model;
    private $db;
    private static $processCount = 0; // ✅ Track how many times process() is called

    public function __construct($db) {
        $this->db = $db;
        $this->model = new CheckoutModel($db);
    }

    public function process($request) {
        // ✅ INCREMENT COUNTER
        self::$processCount++;
        
        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');
        
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        try {
            // ✅ LOG: Process count
            error_log("=== CHECKOUT PROCESS START (CALL #" . self::$processCount . ") ===");
            error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
            error_log("Timestamp: " . date('Y-m-d H:i:s.u'));
            error_log("Request URI: " . $_SERVER['REQUEST_URI']);
            
            // ✅ CRITICAL: Check if already processed
            if (self::$processCount > 1) {
                error_log("⚠️⚠️⚠️ WARNING: process() called " . self::$processCount . " times! ⚠️⚠️⚠️");
                throw new Exception('Transaksi sudah diproses. Mohon jangan submit ulang.');
            }
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid Request Method');
            }

            // Simpan cart ke variabel lokal
            $currentCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            
            error_log("Cart items count: " . count($currentCart));
            error_log("Cart contents: " . json_encode($currentCart));

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
                if (!isset($item['id_product']) || !isset($item['qty'])) {
                    error_log("Invalid cart item: " . json_encode($item));
                    continue;
                }
                
                $cartDataForJson[] = [
                    'id_product' => (int)$item['id_product'],
                    'qty'        => (int)$item['qty']
                ];
            }
            
            if (empty($cartDataForJson)) {
                throw new Exception('Data produk tidak valid.');
            }
            
            error_log("Cart data prepared: " . json_encode($cartDataForJson));
            error_log("🔵 CALLING createTransaction() NOW...");

            // ✅ Proses transaksi - HANYA SEKALI
            $result = $this->model->createTransaction($id_customer, $cartDataForJson);

            if (!$result) {
                throw new Exception('Gagal memproses transaksi ke database.');
            }

            error_log("✅ Transaction successful!");

            // Set flag checkout sukses
            $_SESSION['checkout_success'] = true;
            $_SESSION['checkout_message'] = 'Transaksi berhasil! Terima kasih atas pembelian Anda.';

            // Clear cart
            unset($_SESSION['cart']);
            $_SESSION['cart'] = [];
            
            error_log("Cart cleared");
            error_log("=== CHECKOUT PROCESS END (CALL #" . self::$processCount . ") ===");

            // Clear buffer dan kirim response
            ob_clean();
            
            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil! Terima kasih atas pembelian Anda.',
                'customer_id' => $id_customer,
                'redirect' => 'index.php?page=checkout_success',
                'debug_call_count' => self::$processCount // ✅ Debug info
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            error_log("=== PDO EXCEPTION (CALL #" . self::$processCount . ") ===");
            error_log("Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("=== GENERAL EXCEPTION (CALL #" . self::$processCount . ") ===");
            error_log("Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
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
?>