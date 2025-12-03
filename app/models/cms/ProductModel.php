<?php
// File: app/models/cms/ProductModel.php

class ProductModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * ✅ NEW: Get products with pagination and search
     */
    public function getProductsPaginated($page = 1, $perPage = 10, $search = '') {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Build WHERE clause
            $whereClause = "WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $whereClause .= " AND (
                    p.name ILIKE :search 
                    OR p.sku ILIKE :search
                    OR s.name ILIKE :search
                    OR c.name ILIKE :search
                )";
                $params[':search'] = "%{$search}%";
            }
            
            // Count total records
            $countSql = "
                SELECT COUNT(*) as total
                FROM product p
                LEFT JOIN supplier s ON p.id_supplier = s.id_supplier
                LEFT JOIN category c ON p.id_category = c.id_category
                {$whereClause}
            ";
            
            $stmtCount = $this->db->prepare($countSql);
            foreach ($params as $key => $value) {
                $stmtCount->bindValue($key, $value);
            }
            $stmtCount->execute();
            $totalRecords = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Get paginated data
            $sql = "
                SELECT 
                    p.id_product,
                    p.name AS product_name,
                    p.sku,
                    p.stock,
                    p.price,
                    p.image,
                    p.id_category,
                    p.id_supplier,
                    c.name AS category_name,
                    s.name AS supplier_name,
                    fn_get_stock_status(p.stock) AS status_stok
                FROM product p
                LEFT JOIN supplier s ON p.id_supplier = s.id_supplier
                LEFT JOIN category c ON p.id_category = c.id_category
                {$whereClause}
                ORDER BY p.id_product DESC
                LIMIT :limit OFFSET :offset
            ";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate pagination info
            $totalPages = $totalRecords > 0 ? ceil($totalRecords / $perPage) : 0;
            $from = $totalRecords > 0 ? $offset + 1 : 0;
            $to = min($offset + $perPage, $totalRecords);
            
            return [
                'products' => $products,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total_records' => $totalRecords,
                    'total_pages' => $totalPages,
                    'from' => $from,
                    'to' => $to,
                    'has_previous' => $page > 1,
                    'has_next' => $page < $totalPages
                ]
            ];
            
        } catch (PDOException $e) {
            error_log("ProductModel::getProductsPaginated Error: " . $e->getMessage());
            return [
                'products' => [],
                'pagination' => [
                    'current_page' => 1,
                    'per_page' => $perPage,
                    'total_records' => 0,
                    'total_pages' => 0,
                    'from' => 0,
                    'to' => 0,
                    'has_previous' => false,
                    'has_next' => false
                ]
            ];
        }
    }

    // Get all products with category and supplier info
    public function getAllProducts() {
        try {
            $sql = "SELECT 
                        p.id_product,
                        p.name AS product_name,
                        p.sku,
                        p.stock,
                        p.price,
                        p.image,
                        p.id_category,
                        p.id_supplier,
                        c.name AS category_name,
                        s.name AS supplier_name
                    FROM product p
                    LEFT JOIN category c ON p.id_category = c.id_category
                    LEFT JOIN supplier s ON p.id_supplier = s.id_supplier
                    ORDER BY p.name ASC";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("ProductModel::getAllProducts Error: " . $e->getMessage());
            return [];
        }
    }

    // Get product by ID
    public function getProductById($id) {
        try {
            $sql = "SELECT * FROM product WHERE id_product = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("ProductModel::getProductById Error: " . $e->getMessage());
            return null;
        }
    }

    // Add new product
    public function addProduct($data) {
        try {
            $sql = "INSERT INTO product (id_category, id_supplier, name, sku, stock, price, image) 
                    VALUES (:id_category, :id_supplier, :name, :sku, :stock, :price, :image)
                    RETURNING id_product";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id_category' => $data['id_category'],
                ':id_supplier' => $data['id_supplier'],
                ':name' => $data['name'],
                ':sku' => $data['sku'],
                ':stock' => $data['stock'],
                ':price' => $data['price'],
                ':image' => $data['image']
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['id_product'] ?? false;
            
        } catch (PDOException $e) {
            error_log("ProductModel::addProduct Error: " . $e->getMessage());
            return false;
        }
    }

    // Update product
    public function updateProduct($data) {
        try {
            // Build SQL dynamically based on whether image is being updated
            if (isset($data['image'])) {
                $sql = "UPDATE product 
                        SET id_category = :id_category,
                            id_supplier = :id_supplier,
                            name = :name,
                            sku = :sku,
                            stock = :stock,
                            price = :price,
                            image = :image
                        WHERE id_product = :id_product";
            } else {
                $sql = "UPDATE product 
                        SET id_category = :id_category,
                            id_supplier = :id_supplier,
                            name = :name,
                            sku = :sku,
                            stock = :stock,
                            price = :price
                        WHERE id_product = :id_product";
            }
            
            $stmt = $this->db->prepare($sql);
            
            $params = [
                ':id_category' => $data['id_category'],
                ':id_supplier' => $data['id_supplier'],
                ':name' => $data['name'],
                ':sku' => $data['sku'],
                ':stock' => $data['stock'],
                ':price' => $data['price'],
                ':id_product' => $data['id_product']
            ];
            
            if (isset($data['image'])) {
                $params[':image'] = $data['image'];
            }
            
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("ProductModel::updateProduct Error: " . $e->getMessage());
            return false;
        }
    }

    // Delete product
    public function deleteProduct($id) {
        try {
            $sql = "DELETE FROM product WHERE id_product = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
            
        } catch (PDOException $e) {
            error_log("ProductModel::deleteProduct Error: " . $e->getMessage());
            return false;
        }
    }

    // Get all categories
    public function getCategories() {
        try {
            $sql = "SELECT id_category, name FROM category ORDER BY name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("ProductModel::getCategories Error: " . $e->getMessage());
            return [];
        }
    }

    // Get all suppliers
    public function getSuppliers() {
        try {
            $sql = "SELECT id_supplier, name FROM supplier ORDER BY name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("ProductModel::getSuppliers Error: " . $e->getMessage());
            return [];
        }
    }
}
?>