<?php
// File: ../app/controllers/admin/AuthController.php

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function showLoginForm() {
        // ❌ HAPUS BAGIAN INI (Logika lama):
        /* if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header("Location: index.php?page=admin_dashboard");
            exit;
        }
        */

        // ✅ GANTI DENGAN INI (Logika Baru):
        // Setiap kali halaman login dibuka, kita RESET sesi admin.
        // Ini membuat user "terpaksa" login lagi.
        if (isset($_SESSION['admin_logged_in'])) {
            unset($_SESSION['admin_logged_in']);
            unset($_SESSION['admin_name']);
            // session_destroy(); // Opsional: gunakan ini jika ingin menghapus keranjang belanja juga
        }

        // Tampilkan View Login
        require_once '../app/views/admin/auth/login.php'; 
    }

    public function login($request) {
        $username = $request['username'] ?? '';
        $password = $request['password'] ?? '';

        // Hardcode sementara (Ganti dengan database nanti)
        $valid_user = 'admin';
        $valid_pass = 'adminadmin';

        if ($username === $valid_user && $password === $valid_pass) {
            // Set Session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $username;
            
            // Redirect ke Dashboard
            header("Location: index.php?page=admin_dashboard");
            exit;
        } else {
            echo "<script>
                    alert('Username atau Password salah!');
                    window.location.href = 'index.php?page=login';
                  </script>";
            exit;
        }
    }

    // Fungsi logout tetap ada untuk jaga-jaga, tapi tidak wajib dipakai
    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}
?>