<?php
// File: ../app/controllers/CheckoutController.php

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
        ini_set('display_errors', 0);
        error_reporting(0); // Suppress all errors for clean JSON

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid Request Method');
            }

            // Simpan cart ke variabel lokal
            $currentCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

            if (empty($currentCart)) {
                throw new Exception('Keranjang belanja kosong.');
            }

            $name = trim($request['name'] ?? '');
            $email = trim($request['email'] ?? '');
            $address = trim($request['address'] ?? '');

            if (empty($name) || empty($email) || empty($address)) {
                throw new Exception('Mohon lengkapi semua data.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Format email tidak valid.');
            }

            // Get atau Create Customer
            $id_customer = $this->model->getOrCreateCustomer($name, $email, $address);
            
            if (!$id_customer) {
                throw new Exception("Gagal mendapatkan ID Customer.");
            }

            // Siapkan data cart
            $cartDataForJson = [];
            foreach ($currentCart as $item) {
                $cartDataForJson[] = [
                    'id_product' => (int)$item['id_product'],
                    'qty'        => (int)$item['qty']
                ];
            }

            // Proses transaksi
            $result = $this->model->createTransaction($id_customer, $cartDataForJson);

            if (!$result) {
                throw new Exception('Gagal memproses transaksi ke database.');
            }

            // Set flag checkout sukses SEBELUM clear cart
            $_SESSION['checkout_success'] = true;
            $_SESSION['checkout_message'] = 'Transaksi berhasil! Terima kasih atas pembelian Anda.';

            // Clear cart
            unset($_SESSION['cart']);
            $_SESSION['cart'] = [];

            // Clear buffer dan kirim response
            ob_clean();
            
            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil! Terima kasih atas pembelian Anda.',
                'customer_id' => $id_customer,
                'redirect' => 'index.php?page=checkout_success'
            ], JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
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