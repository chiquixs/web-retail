<?php
class HomeController {

    // Konstruktor (walaupun tidak dipakai, tetap dipertahankan)
    public function __construct($db) {
        // Tidak ada Model yang dipanggil untuk Home
    }

    public function index() {
        // Langsung panggil tampilan Home
        require_once '../app/views/home/index.php';
    }
}
?>