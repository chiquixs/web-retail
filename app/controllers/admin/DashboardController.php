<?php
// File: ../app/controllers/admin/DashboardController.php

class DashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // Di sini nanti kita bisa ambil data statistik (misal: jumlah produk, total penjualan)
        // Contoh: $totalProduk = $this->model->getTotalProducts();
        
        // Panggil View Dashboard
        // Pastikan path ini sesuai dengan struktur foldermu
        require_once '../app/views/admin/dashboard/index.php'; 
    }
}
?>