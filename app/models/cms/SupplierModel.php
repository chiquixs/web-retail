<?php
class SupplierModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllSuppliers() {
        try {
            $sql = "SELECT id_supplier, name, email, address FROM supplier ORDER BY name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SupplierModel::getAllSuppliers Error: " . $e->getMessage());
            return [];
        }
    }

    public function getSupplierById($id) {
        try {
            $sql = "SELECT * FROM supplier WHERE id_supplier = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SupplierModel::getSupplierById Error: " . $e->getMessage());
            return null;
        }
    }

    public function addSupplier($data) {
        try {
            $sql = "INSERT INTO supplier (name, email, address) 
                    VALUES (:name, :email, :address) 
                    RETURNING id_supplier";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':address' => $data['address']
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['id_supplier'] ?? false;
        } catch (PDOException $e) {
            error_log("SupplierModel::addSupplier Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateSupplier($data) {
        try {
            $sql = "UPDATE supplier 
                    SET name = :name, email = :email, address = :address 
                    WHERE id_supplier = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':address' => $data['address'],
                ':id' => $data['id_supplier']
            ]);
        } catch (PDOException $e) {
            error_log("SupplierModel::updateSupplier Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSupplier($id) {
        try {
            $sql = "DELETE FROM supplier WHERE id_supplier = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("SupplierModel::deleteSupplier Error: " . $e->getMessage());
            return false;
        }
    }
}