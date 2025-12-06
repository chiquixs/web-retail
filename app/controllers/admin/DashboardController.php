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
        
        // BEST SELLING PRODUCTS
        $stmt = $this->db->query("SELECT * FROM mv_best_selling_products");
        $bestSelling = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // PROFIT LOSS DAILY
        $stmt = $this->db->query("SELECT * FROM vw_profit_loss_daily");
        $profitLoss = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // STOCK MONITOR
        $stmt = $this->db->query("SELECT * FROM vw_stock_monitor");
        $stockMonitor = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // HISTORY TRANSAKSI
        $stmt = $this->db->query("SELECT * FROM vw_transaction_history");
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Data dikirim ke view
        $data = [
            'totalProducts'     => $totalProducts,
            'totalCustomers'    => $totalCustomers,
            'totalTransactions' => $totalTransactions,
            'totalSuppliers'     => $totalSuppliers,
            'totalCategories'     => $totalCategories,
            'bestSelling'       => $bestSelling,
            'profitLoss'        => $profitLoss,
            'stockMonitor'        => $stockMonitor,
            'history'        => $history,
        ];
        // Di sini nanti kita bisa ambil data statistik (misal: jumlah produk, total penjualan)
        // Contoh: $totalProduk = $this->model->getTotalProducts();
        
        // Panggil View Dashboard
        // Pastikan path ini sesuai dengan struktur foldermu
        require_once '../app/views/admin/dashboard/index.php'; 
    }

    public function getDailySalesData() {
        $sql = "select sales_date, total_revenue 
                from mv_daily_sales_summary
                order by sales_date asc";

        $stmt = $this->db->query($sql);

        $dates = [];
        $revenues = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dates[] = $row['sales_date'];
            $revenues[] = $row['total_revenue'];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'dates' => $dates,
            'revenues' => $revenues
        ]);
    }

    public function refreshDailySalesMV(){
        try {
            $sql = "REFRESH MATERIALIZED VIEW mv_daily_sales_summary";
            $this->db->exec($sql);

            $_SESSION['success_message'] = "Materialized view mv_daily_sales_summary berhasil direfresh!";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Gagal refresh: " . $e->getMessage();
        }

        header("Location: index.php?page=admin_dashboard");
        exit;
    }

    public function getBestSellingProducts() {
        $sql = "Select * from mv_best_selling_products";

        $stmt = $this->db->query($sql);
        $bestSelling = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($bestSelling);
        exit;    
    }

    public function refreshBestSellingProductsMV(){
        try {
            $sql = "REFRESH MATERIALIZED VIEW mv_best_selling_products";
            $this->db->exec($sql);

            $_SESSION['best_selling_refresh_success'] = "Materialized view mv_best_selling_products berhasil direfresh!";
        } catch (Exception $e) {
            $_SESSION['best_selling_refresh_error'] = "Gagal refresh: " . $e->getMessage();
        }

        header("Location: index.php?page=admin_dashboard");
        exit;
    }

    public function getProfitLoss() {
        $sql = "Select * from  vw_profit_loss_daily";

        $stmt = $this->db->query($sql);
        $profitLoss = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($profitLoss);
        exit;    
    }

    public function getStockMonitor() {
        $sql = "Select * from  vw_stock_monitor";

        $stmt = $this->db->query($sql);
        $stockMonitor = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($stockMonitor);
        exit;    
    }

    public function getHistory() {
        $sql = "Select * from  vw_transaction_history";

        $stmt = $this->db->query($sql);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($history);
        exit;    
    }
}
?>