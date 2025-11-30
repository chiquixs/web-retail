<?php
class CategoryModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllCategories() {
        try {
            $sql = "SELECT id_category, name, description FROM category ORDER BY name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CategoryModel::getAllCategories Error: " . $e->getMessage());
            return [];
        }
    }

    public function getCategoryById($id) {
        try {
            $sql = "SELECT * FROM category WHERE id_category = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CategoryModel::getCategoryById Error: " . $e->getMessage());
            return null;
        }
    }

    public function addCategory($data) {
        try {
            $sql = "INSERT INTO category (name, description) 
                    VALUES (:name, :description) 
                    RETURNING id_category";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description']
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['id_category'] ?? false;
        } catch (PDOException $e) {
            error_log("CategoryModel::addCategory Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateCategory($data) {
        try {
            $sql = "UPDATE category 
                    SET name = :name, description = :description 
                    WHERE id_category = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':id' => $data['id_category']
            ]);
        } catch (PDOException $e) {
            error_log("CategoryModel::updateCategory Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            $sql = "DELETE FROM category WHERE id_category = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("CategoryModel::deleteCategory Error: " . $e->getMessage());
            return false;
        }
    }
}