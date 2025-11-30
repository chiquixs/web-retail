<?php
// File: app/controllers/HomeController.php

require_once '../app/models/HomeModel.php';

class HomeController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new HomeModel($db);
    }

    public function index() {
        // Get top 3 best selling products
        $topProducts = $this->model->getTopSellingProducts(3);
        
        // Pass data to view
        $data = [
            'topProducts' => $topProducts,
            'pageTitle' => 'Home - Blace Furniture'
        ];
        
        // Load view
        require_once '../app/views/home/index.php';
    }
}
?>