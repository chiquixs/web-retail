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
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid Request Method');
            }

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                throw new Exception('Keranjang belanja kosong.');
            }

            $name = trim($request['name'] ?? '');
            $email = trim($request['email'] ?? '');
            $address = trim($request['address'] ?? '');

            if (empty($name) || empty($email) || empty($address)) {
                throw new Exception('Mohon lengkapi data diri.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Format email tidak valid.');
            }

            $id_customer = $this->model->getOrCreateCustomer($name, $email, $address);
            
            if (!$id_customer) {
                throw new Exception("Gagal mendapatkan ID Customer.");
            }

            $cartDataForJson = [];
            foreach ($_SESSION['cart'] as $item) {
                $cartDataForJson[] = [
                    'id_product' => (int)$item['id_product'],
                    'qty'        => (int)$item['qty']
                ];
            }

            $result = $this->model->createTransaction($id_customer, $cartDataForJson);

            if (!$result) {
                throw new Exception('Gagal memproses transaksi.');
            }

            unset($_SESSION['cart']);

            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil! Terima kasih atas pembelian Anda.',
                'customer_id' => $id_customer
            ]);

        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
?>