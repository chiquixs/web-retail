<?php
// File: app/models/HomeModel.php

class HomeModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Get top selling products based on total quantity sold
     * 
     * @param int $limit Number of products to return
     * @return array Array of top selling products
     */
    public function getTopSellingProducts($limit = 3) {
        try {
            $sql = "
                SELECT 
                    p.id_product,
                    p.name,
                    p.price,
                    p.image,
                    p.stock,
                    COALESCE(SUM(td.qty), 0) as total_sold
                FROM product p
                LEFT JOIN transaction_detail td ON p.id_product = td.id_product
                WHERE p.stock > 0
                GROUP BY p.id_product, p.name, p.price, p.image, p.stock
                ORDER BY total_sold DESC
                LIMIT :limit
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // If no products found with sales, get random products
            if (empty($products)) {
                return $this->getRandomProducts($limit);
            }
            
            return $products;
            
        } catch (PDOException $e) {
            error_log("HomeModel::getTopSellingProducts Error: " . $e->getMessage());
            // Fallback to random products if query fails
            return $this->getRandomProducts($limit);
        }
    }

    /**
     * Get random products as fallback
     * 
     * @param int $limit Number of products to return
     * @return array Array of random products
     */
    private function getRandomProducts($limit = 3) {
        try {
            $sql = "
                SELECT 
                    id_product,
                    name,
                    price,
                    image,
                    stock,
                    0 as total_sold
                FROM product
                WHERE stock > 0
                ORDER BY RANDOM()
                LIMIT :limit
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("HomeModel::getRandomProducts Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all products for shop page
     * 
     * @return array Array of all products
     */
    public function getAllProducts() {
        try {
            $sql = "
                SELECT 
                    id_product,
                    name,
                    price,
                    image,
                    stock
                FROM product
                WHERE stock > 0
                ORDER BY name ASC
            ";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("HomeModel::getAllProducts Error: " . $e->getMessage());
            return [];
        }
    }
}
?>