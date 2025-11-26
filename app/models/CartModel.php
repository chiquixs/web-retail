<?php
class CartModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getProductInfo($productId) {
        if ((int)$productId <= 0) {
            return null;
        }

        $sql = "SELECT id_product, name, price, image 
                FROM product 
                WHERE id_product = :id_product";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_product', $productId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result : null;
        } catch (PDOException $e) {
            error_log("CartModel getProductInfo Error: " . $e->getMessage());
            return null;
        }
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
    // 1. Cek Customer yang sudah ada
    $stmtCheck = $this->db->prepare("SELECT id_customer FROM customer WHERE email = :email LIMIT 1");
    $stmtCheck->execute([':email' => $email]);
    $existingCust = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($existingCust) {
        return (int)$existingCust['id_customer']; // User Lama
    } else {
        // 2. User Baru: Insert & Ambil ID
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
        
        if ($newCust) {
            return (int)$newCust['id_customer'];
        }
    }
    return 0; // Gagal mendapatkan ID Customer
}

public function createTransaction($id_customer, $jsonPayload) {
    // PANGGIL STORED PROCEDURE
    // SQL: CALL sp_add_transaction(:id_cust::int, :json_data::jsonb)
    
    $sql = "CALL sp_add_transaction(:id_cust, :json_data)"; // Hapus ::int, ::jsonb jika pakai MySQL/MariaDB
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_cust', $id_customer, PDO::PARAM_INT);
    $stmt->bindParam(':json_data', $jsonPayload); 
    
    // Khusus PostgreSQL, Anda mungkin perlu mengatur tipe data binding secara eksplisit
    // Jika Anda menggunakan driver yang mendukung:
    // $stmt->bindParam(':json_data', $jsonPayload, PDO::PARAM_STR); 
    
    return $stmt->execute();
}

}
?>