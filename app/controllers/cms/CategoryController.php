<?php
require_once '../app/models/cms/CategoryModel.php';

class CategoryController {
    private $model;

    public function __construct($db) {
        $this->model = new CategoryModel($db);
    }

    public function index() {
        $categories = $this->model->getAllCategories();
        
        $data = [
            'categories' => $categories
        ];
        
        require_once '../app/views/cms/category/index.php';
    }

    public function add($request) {
        header('Content-Type: application/json');
        error_reporting(0);
        ini_set('display_errors', 0);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $name = trim($request['name'] ?? '');
            $description = trim($request['description'] ?? '');

            if (empty($name)) {
                throw new Exception('Category name is required');
            }

            $result = $this->model->addCategory([
                'name' => $name,
                'description' => $description
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Category added successfully'
                ]);
            } else {
                throw new Exception('Failed to add category');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function update($request) {
        header('Content-Type: application/json');
        error_reporting(0);
        ini_set('display_errors', 0);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $id = $request['id_category'] ?? null;
            $name = trim($request['name'] ?? '');
            $description = trim($request['description'] ?? '');

            if (empty($id)) {
                throw new Exception('Category ID not found');
            }

            if (empty($name)) {
                throw new Exception('Category name is required');
            }

            $result = $this->model->updateCategory([
                'id_category' => $id,
                'name' => $name,
                'description' => $description
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Category updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update category');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function delete($request) {
        header('Content-Type: application/json');
        error_reporting(0);
        ini_set('display_errors', 0);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $id = $request['id_category'] ?? null;

            if (empty($id)) {
                throw new Exception('Category ID not found');
            }

            $result = $this->model->deleteCategory($id);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Category deleted successfully'
                ]);
            } else {
                throw new Exception('Failed to delete category');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}