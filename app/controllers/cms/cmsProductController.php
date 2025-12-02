<?php
// File: ../app/controllers/cms/CmsProductController.php

require_once '../app/models/cms/ProductModel.php';

class ProductController {  // ✅ Hapus "Cms" dari nama class
    private $model;

    

    public function __construct($db) {
        $this->model = new ProductModel($db);
    }
    
    // Display product list page
    public function index() {
        // Get parameters
        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $perPage = 10;
        
        // Check if AJAX request
        $isAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';
        
        // Get paginated data
        $result = $this->model->getProductsPaginated($page, $perPage, $search);
        
        // If AJAX, return JSON
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
        
        // Regular page load
        $data = [
            'products' => $result['products'],
            'pagination' => $result['pagination'],
            'search' => $search
        ];
        
        require_once '../app/views/cms/product/index.php';
    }

    // Add new product
    public function add($request) {
        header('Content-Type: application/json');
        error_reporting(0);
        ini_set('display_errors', 0);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Validate input
            $name = trim($request['name'] ?? '');
            $sku = trim($request['sku'] ?? '');
            $id_category = (int)($request['id_category'] ?? 0);
            $id_supplier = (int)($request['id_supplier'] ?? 0);
            $stock = (int)($request['stock'] ?? 0);
            $price = (float)($request['price'] ?? 0);

            if (empty($name) || empty($sku) || $id_category <= 0 || $id_supplier <= 0 || $price <= 0) {
                throw new Exception('All required fields must be filled');
            }

            // Handle image upload
            $imageName = $this->handleImageUpload($sku);

            // Add product via model
            $result = $this->model->addProduct([
                'name' => $name,
                'sku' => $sku,
                'id_category' => $id_category,
                'id_supplier' => $id_supplier,
                'stock' => $stock,
                'price' => $price,
                'image' => $imageName
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product added successfully'
                ]);
            } else {
                throw new Exception('Failed to add product');
            }

        } catch (Exception $e) {
            // Clean up uploaded image if exists
            if (isset($imageName) && $imageName) {
                $uploadPath = '../public/assets/images/products/' . $imageName;
                if (file_exists($uploadPath)) {
                    unlink($uploadPath);
                }
            }

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Update product
    public function update($request) {
        header('Content-Type: application/json');
        error_reporting(0);
        ini_set('display_errors', 0);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $id = $request['id_product'] ?? null;
            $name = trim($request['name'] ?? '');
            $sku = trim($request['sku'] ?? '');
            $id_category = (int)($request['id_category'] ?? 0);
            $id_supplier = (int)($request['id_supplier'] ?? 0);
            $stock = (int)($request['stock'] ?? 0);
            $price = (float)($request['price'] ?? 0);

            if (empty($id)) {
                throw new Exception('Product ID not found');
            }

            if (empty($name) || empty($sku) || $id_category <= 0 || $id_supplier <= 0 || $price <= 0) {
                throw new Exception('All required fields must be filled correctly');
            }

            // Prepare data for update
            $data = [
                'id_product' => $id,
                'name' => $name,
                'sku' => $sku,
                'id_category' => $id_category,
                'id_supplier' => $id_supplier,
                'stock' => $stock,
                'price' => $price
            ];

            // Handle optional image upload
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->handleImageUpload($sku, true);
                $data['image'] = $imageName;
            }

            // Update via model
            $result = $this->model->updateProduct($data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update product');
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Delete product
    public function delete($request) {
        header('Content-Type: application/json');
        error_reporting(0);
        ini_set('display_errors', 0);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $id = $request['id_product'] ?? null;

            if (empty($id)) {
                throw new Exception('Product ID not found');
            }

            // Get product details first (to delete image)
            $product = $this->model->getProductById($id);

            if (!$product) {
                throw new Exception('Product not found');
            }

            // Delete image file
            if (!empty($product['image'])) {
                $filePath = '../public/assets/images/products/' . $product['image'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete from database
            $result = $this->model->deleteProduct($id);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            } else {
                throw new Exception('Failed to delete product');
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Get categories (for dropdown)
    public function getCategories() {
        header('Content-Type: application/json');
        
        try {
            $categories = $this->model->getCategories();
            echo json_encode([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Get suppliers (for dropdown)
    public function getSuppliers() {
        header('Content-Type: application/json');
        
        try {
            $suppliers = $this->model->getSuppliers();
            echo json_encode([
                'success' => true,
                'suppliers' => $suppliers
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Private method to handle image upload
    private function handleImageUpload($sku, $isUpdate = false) {
        if (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            if ($isUpdate) {
                return null; // No new image for update
            }
            throw new Exception('Product image is required');
        }

        $uploadDir = '../public/assets/images/products/';

        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Only JPG, PNG, WEBP allowed');
        }

        // Check file size (max 2MB)
        if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            throw new Exception('File too large. Maximum 2MB');
        }

        // Generate unique filename
        $cleanSku = preg_replace('/[^A-Za-z0-9\-]/', '', $sku);
        $imageName = $cleanSku . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to upload image');
        }

        return $imageName;
    }
}
?>