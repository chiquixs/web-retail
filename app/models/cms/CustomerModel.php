<?php
class CustomerModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllCustomers() {
        try {
            $sql = "SELECT id_customer, name, email, address FROM customer ORDER BY name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CustomerModel::getAllCustomers Error: " . $e->getMessage());
            return [];
        }
    }

    public function getCustomerById($id) {
        try {
            $sql = "SELECT * FROM customer WHERE id_customer = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CustomerModel::getCustomerById Error: " . $e->getMessage());
            return null;
        }
    }

    public function addCustomer($data) {
        try {
            $sql = "INSERT INTO customer (name, email, address)
                    VALUES (:name, :email, :address)
                    RETURNING id_customer";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name'    => $data['name'],
                ':email'   => $data['email'],
                ':address' => $data['address']
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['id_customer'] ?? false;
        } catch (PDOException $e) {
            error_log("CustomerModel::addCustomer Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateCustomer($data) {
        try {
            $sql = "UPDATE customer
                    SET name = :name, email = :email, address = :address
                    WHERE id_customer = :id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name'    => $data['name'],
                ':email'   => $data['email'],
                ':address' => $data['address'],
                ':id'      => $data['id_customer']
            ]);
        } catch (PDOException $e) {
            error_log("CustomerModel::updateCustomer Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCustomer($id) {
        try {
            $sql = "DELETE FROM customer WHERE id_customer = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("CustomerModel::deleteCustomer Error: " . $e->getMessage());
            return false;
        }
    }
}