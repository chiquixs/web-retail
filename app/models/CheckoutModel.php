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

public function createTransaction($id_customer, $cartData) {
    try {
        // Validasi input
        if (empty($cartData)) {
            throw new Exception("Data keranjang kosong.");
        }

        // LANGKAH 1: Ubah Array PHP menjadi String JSON
        $jsonPayload = json_encode($cartData);

        if ($jsonPayload === false) {
            throw new Exception("Gagal mengubah data keranjang menjadi JSON.");
        }

        // Log untuk debugging (opsional, bisa dihapus di production)
        error_log("Creating transaction for customer: $id_customer");
        error_log("Cart JSON: $jsonPayload");

        // LANGKAH 2: Siapkan Query
        $sql = "CALL sp_add_transaction(:id_cust, CAST(:json_data AS jsonb))";
        
        $stmt = $this->db->prepare($sql);
        
        // LANGKAH 3: Binding Parameter
        $stmt->bindParam(':id_cust', $id_customer, PDO::PARAM_INT);
        $stmt->bindParam(':json_data', $jsonPayload, PDO::PARAM_STR); 
        
        // LANGKAH 4: Eksekusi
        $result = $stmt->execute();
        
        if (!$result) {
            throw new Exception("Gagal mengeksekusi stored procedure.");
        }
        
        error_log("Transaction created successfully!");
        
        return true;

    } catch (PDOException $e) {
        // Log error lengkap untuk developer
        error_log("CheckoutModel::createTransaction SQL Error: " . $e->getMessage());
        error_log("SQL State: " . $e->getCode());
        
        // Tangkap pesan error dari stored procedure (RAISE EXCEPTION)
        $errorMsg = $e->getMessage();
        
        // Cek apakah error dari validasi stok di SP
        if (strpos($errorMsg, 'Stok tidak cukup') !== false) {
            throw new Exception($errorMsg);
        } elseif (strpos($errorMsg, 'tidak ditemukan') !== false) {
            throw new Exception($errorMsg);
        } else {
            throw new Exception("Gagal memproses transaksi: " . $errorMsg);
        }
    }
}
}