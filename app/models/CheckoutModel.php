<?php
// File: ../app/models/CheckoutModel.php

class CheckoutModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

      // Fungsi utama: Mengambil detail semua produk yang ada di keranjang
    public function getCartDetails($productIds) {
        if (empty($productIds)) {
            return [];
        }

        // Membuat placeholder untuk query IN (contoh: ?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        
        // Query untuk mengambil detail produk
        $sql = "SELECT id_product, name, price, image FROM product WHERE id_product IN ($placeholders)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($productIds);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Dalam kasus error database, return array kosong
            error_log("CartModel Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getOrCreateCustomer($name, $email, $address) {
        try {
            $stmtCheck = $this->db->prepare("SELECT id_customer FROM customer WHERE email = :email LIMIT 1");
            $stmtCheck->execute([':email' => $email]);
            $existingCust = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existingCust) {
                return (int)$existingCust['id_customer'];
            }

            $sqlInsert = "INSERT INTO customer (name, email, address) 
                          VALUES (:name, :email, :address) 
                          RETURNING id_customer";
            
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([
                ':name' => $name,
                ':email' => $email,
                ':address' => $address
            ]);
            
            $newCust = $stmtInsert->fetch(PDO::FETCH_ASSOC);
            return (int)$newCust['id_customer'];

        } catch (PDOException $e) {
            error_log("CheckoutModel::getOrCreateCustomer Error: " . $e->getMessage());
            return 0;
        }
    }

    public function createTransaction($id_customer, $cartData) {
        try {
            $jsonPayload = json_encode($cartData);

            $sql = "CALL sp_add_transaction(:id_cust::int, :json_data::jsonb)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_cust', $id_customer, PDO::PARAM_INT);
            $stmt->bindParam(':json_data', $jsonPayload);
            
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("CheckoutModel::createTransaction Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
