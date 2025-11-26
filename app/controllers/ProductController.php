<?php
// Load Model dulu
require_once '../app/models/ProductModel.php';

class ProductController {
    private $model;

    public function __construct($db) {
        // Inisialisasi Model
        $this->model = new ProductModel($db);
    }

    public function index() {
        // 1. Minta data ke Model
        $products = $this->model->getAllProducts();

        // 2. Kirim data ke View (Tampilan)
        // Kita simpan di folder views/shop/index.php
        require_once '../app/views/shop/index.php';
    }
}
?>