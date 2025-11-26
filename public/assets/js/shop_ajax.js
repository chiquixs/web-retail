// File: public/assets/js/shop_ajax.js

// Load products (Digunakan untuk menampilkan produk secara dinamis, tapi kamu sudah pakai PHP loop sekarang)
async function loadProducts() {
    try {
        console.log('Fetching products...');
        // Jika kamu masih loading produk via AJAX, pastikan URL ini benar
        const response = await fetch('index.php?page=get_products_ajax'); 
        const data = await response.json();

        const grid = document.getElementById('product-grid');
        const loading = document.getElementById('loading-spinner');

        if (data.success && data.products.length > 0) {
            // ... (Logika mapping produk yang sekarang kamu lakukan di PHP) ...
            loading.style.display = 'none';
            grid.style.display = 'flex';
        } else {
            // ... (Logika produk kosong) ...
            loading.style.display = 'none';
            grid.style.display = 'block';
        }
    } catch (error) {
        console.error('Error loading products:', error);
        // ... (Logika error) ...
    }
}

// Add to cart (DIUBAH UNTUK MVC)
async function addToCart(event, id, name, price) {
    event.preventDefault();
    event.stopPropagation();
    
    try {
        const formData = new FormData();
        // Mengirim data lengkap ke CartController->add() via POST
        formData.append('id_product', id);
        formData.append('name', name);
        formData.append('price', price);
        formData.append('qty', 1);

        // PERUBAHAN KRITIS: Ganti 'cart_add.php' ke rute MVC
        const response = await fetch('index.php?page=add_cart', { // ✅ Sinkron dengan case 'add_cart' di PHP
    method: 'POST',
    body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Update cart count (Badge Merah)
            const cartBadge = document.getElementById('cart-count');
            // ... (Logika update atau create cart badge dari kode lama kamu) ...
            if (cartBadge) {
                cartBadge.textContent = data.cart_count;
            } else {
                 // Logika membuat badge jika belum ada (gunakan fungsi updateCartBadge yang kita buat sebelumnya)
                 // Karena badge diinisialisasi di PHP, kita hanya perlu update teksnya
                 let cartLink = document.querySelector('a[href="index.php?page=cart"]');
                 if (cartLink) {
                    let badge = document.createElement('span');
                    badge.id = 'cart-count';
                    badge.className = 'cart-badge';
                    badge.textContent = data.cart_count;
                    cartLink.appendChild(badge);
                 }
            }

            // Show toast (Pesan sukses)
            const toast = document.getElementById('success-toast');
            if (toast) {
                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 2000);
            }
        } else {
            alert('Failed: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to add to cart');
    }
}

// Load on page ready
document.addEventListener('DOMContentLoaded', function() {
    // loadProducts(); // Jika kamu sudah menggunakan PHP loop, baris ini mungkin tidak diperlukan
});

