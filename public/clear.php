<?php
session_start();

// 1. Cek apakah sesi keranjang ada
if (isset($_SESSION['cart'])) {
    // 2. Hapus (unset) seluruh array keranjang
    unset($_SESSION['cart']);
}

// 3. Arahkan pengguna kembali ke halaman utama atau halaman Shop
header('Location: index.php?page=shop');
exit;
?>