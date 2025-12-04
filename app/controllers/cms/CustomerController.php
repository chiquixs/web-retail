<?php
require_once '../app/models/cms/CustomerModel.php';

class CustomerController {
    private $model;

    public function __construct($db) {
        $this->model = new CustomerModel($db);
    }

    public function index() {
        $customers = $this->model->getAllCustomers();

        $data = [
            'customers' => $customers
        ];

        require_once '../app/views/cms/customer/index.php';
    }

    public function add($request) {
        header('Content-Type: application/json');
        error_reporting(0);
        ini_set('display_errors', 0);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $name    = trim($request['name'] ?? '');
            $email   = trim($request['email'] ?? '');
            $address = trim($request['address'] ?? '');

            if (empty($name)) {
                throw new Exception('Customer name is required');
            }

            $result = $this->model->addCustomer([
                'name' => $name,
                'email' => $email,
                'address' => $address
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Customer added successfully'
                ]);
            } else {
                throw new Exception('Failed to add customer');
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

            $id      = $request['id_customer'] ?? null;
            $name    = trim($request['name'] ?? '');
            $email   = trim($request['email'] ?? '');
            $address = trim($request['address'] ?? '');

            if (empty($id)) {
                throw new Exception('Customer ID not found');
            }

            if (empty($name)) {
                throw new Exception('Customer name is required');
            }

            $result = $this->model->updateCustomer([
                'id_customer' => $id,
                'name'        => $name,
                'email'       => $email,
                'address'     => $address
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Customer updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update customer');
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

            $id = $request['id_customer'] ?? null;

            if (empty($id)) {
                throw new Exception('Customer ID not found');
            }

            $result = $this->model->deleteCustomer($id);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Customer deleted successfully'
                ]);
            } else {
                throw new Exception('Failed to delete customer');
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