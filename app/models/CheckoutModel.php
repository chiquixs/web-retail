<?php
// File: ../app/models/CheckoutModel.php

class CheckoutModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Mengambil detail produk
    public function getCartDetails($productIds) {
        if (empty($productIds)) {
            return [];
        }
        // PostgreSQL menggunakan $1, $2, dll untuk placeholder, tapi PDO bisa pakai ?
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $sql = "SELECT id_product, name, price, image FROM product WHERE id_product IN ($placeholders)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($productIds);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CartModel Error: " . $e->getMessage());
            return [];
        }
    }
    
    // Get atau Create Customer (PostgreSQL Version)
    public function getOrCreateCustomer($name, $email, $address) {
        try {
            // 1. Cek User
            $stmtCheck = $this->db->prepare("SELECT id_customer FROM customer WHERE email = :email LIMIT 1");
            $stmtCheck->execute([':email' => $email]);
            $existingCust = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existingCust) {
                return (int)$existingCust['id_customer'];
            }

            // 2. Insert User (Menggunakan syntax RETURNING id_customer milik PostgreSQL)
            $sqlInsert = "INSERT INTO customer (name, email, address) 
                          VALUES (:name, :email, :address) 
                          RETURNING id_customer";
            
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([
                ':name' => $name,
                ':email' => $email,
                ':address' => $address
            ]);
            
            // FetchColumn untuk mengambil hasil RETURNING
            return (int)$stmtInsert->fetchColumn();

        } catch (PDOException $e) {
            error_log("CheckoutModel::getOrCreateCustomer Error: " . $e->getMessage());
            return 0;
        }
    }

    // 🟢 EKSEKUSI PROCEDURE POSTGRESQL
    public function createTransaction($id_customer, $cartData) {
        try {
            // 1. Ubah array PHP menjadi JSON String
            $jsonPayload = json_encode($cartData);

            // 2. Panggil Procedure
            // PENTING: Kita casting ::jsonb di dalam query agar PostgreSQL paham
            $sql = "CALL sp_add_transaction(:id_cust, :json_data::jsonb)";
            
            $stmt = $this->db->prepare($sql);
            
            // Binding parameter dengan tipe data eksplisit
            $stmt->bindParam(':id_cust', $id_customer, PDO::PARAM_INT); // Pastikan INT
            $stmt->bindParam(':json_data', $jsonPayload, PDO::PARAM_STR); // Kirim sebagai string, nanti dicast ::jsonb oleh SQL
            
            $stmt->execute();
            
            return true;

        } catch (PDOException $e) {
            error_log("CheckoutModel::createTransaction Error: " . $e->getMessage());
            // Tangkap pesan error dari "RAISE EXCEPTION" di prosedur SQL
            throw new Exception($e->getMessage());
            return false;
        }
    }
}