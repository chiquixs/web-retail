<?php
// File: ../app/controllers/admin/DashboardController.php

class DashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // TOTAL PRODUCT
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM product");
        $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // TOTAL CUSTOMER
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM customer");
        $totalCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // TOTAL TRANSACTIONS
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM transactions");
        $totalTransactions = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // TOTAL SUPPLIERS
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM supplier");
        $totalSuppliers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // TOTAL CATEGORIES
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM category");
        $totalCategories = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Data dikirim ke view
        $data = [
            'totalProducts'     => $totalProducts,
            'totalCustomers'    => $totalCustomers,
            'totalTransactions' => $totalTransactions,
            'totalSuppliers'     => $totalSuppliers,
            'totalCategories'     => $totalCategories,
        ];
        // Di sini nanti kita bisa ambil data statistik (misal: jumlah produk, total penjualan)
        // Contoh: $totalProduk = $this->model->getTotalProducts();
        
        // Panggil View Dashboard
        // Pastikan path ini sesuai dengan struktur foldermu
        require_once '../app/views/admin/dashboard/index.php'; 
    }
}
?>