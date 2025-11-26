<?php
class ProductModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllProducts() {
        // Sesuaikan nama tabel kamu (product atau products?)
        // Di sini saya pakai 'product' sesuai file checkout sebelumnya
        $stmt = $this->db->prepare("SELECT * FROM product ORDER BY id_product ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>