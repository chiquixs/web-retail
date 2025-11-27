<?php
// require_once '../app/models/AuthModel.php'; // Akan dibuat nanti jika perlu interaksi DB

class AuthController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Menampilkan formulir login.
     */
    public function showLoginForm()
    {
        // Asumsi View Login ada di app/cms/auth/login.php
        require_once '../app/cms/auth/login.php';
    }


    public function processLogin()
    {
        session_start();

        $valid_username = "admin";
        $valid_password = "adminadmin";

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === $valid_username && $password === $valid_password) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: /web-retail-rev/app/cms/dashboard/index.php?page=dashboard");
            exit();
        } else {
            echo "<script>
                alert('Username atau password salah!');
                window.location.href='index.php?page=login';
              </script>";
        }
    }
}
