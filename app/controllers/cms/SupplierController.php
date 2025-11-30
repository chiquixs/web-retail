<?php
require_once '../app/models/cms/SupplierModel.php';

class SupplierController {
    private $model;

    public function __construct($db) {
        $this->model = new SupplierModel($db);
    }

    public function index() {
        $suppliers = $this->model->getAllSuppliers();
        
        $data = [
            'suppliers' => $suppliers
        ];
        
        require_once '../app/views/cms/supplier/index.php';
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
            $email = trim($request['email'] ?? '');
            $address = trim($request['address'] ?? '');

            if (empty($name)) {
                throw new Exception('Supplier name is required');
            }

            $result = $this->model->addSupplier([
                'name' => $name,
                'email' => $email,
                'address' => $address
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Supplier added successfully'
                ]);
            } else {
                throw new Exception('Failed to add supplier');
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

            $id = $request['id_supplier'] ?? null;
            $name = trim($request['name'] ?? '');
            $email = trim($request['email'] ?? '');
            $address = trim($request['address'] ?? '');

            if (empty($id)) {
                throw new Exception('Supplier ID not found');
            }

            if (empty($name)) {
                throw new Exception('Supplier name is required');
            }

            $result = $this->model->updateSupplier([
                'id_supplier' => $id,
                'name' => $name,
                'email' => $email,
                'address' => $address
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Supplier updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update supplier');
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

            $id = $request['id_supplier'] ?? null;

            if (empty($id)) {
                throw new Exception('Supplier ID not found');
            }

            $result = $this->model->deleteSupplier($id);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Supplier deleted successfully'
                ]);
            } else {
                throw new Exception('Failed to delete supplier');
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