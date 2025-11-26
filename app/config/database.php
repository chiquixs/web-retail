<?php
class Koneksi {
    private $host = "localhost";
    private $port = "5432";
    // PERHATIKAN: Ganti nama database sesuai project ini
    private $dbname = "db_toko_retail"; 
    private $user = "postgres";
    private $password = "Nafisachiqui3006_"; // Isi password pgAdmin kamu jika ada

    public $conn;

    public function getKoneksi()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname,
                $this->user,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;

        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
}
?>