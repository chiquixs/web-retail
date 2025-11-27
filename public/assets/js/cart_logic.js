// ===== CART LOGIC - Fixed Version =====

function formatRupiah(angka) {
    return 'Rp' + new Intl.NumberFormat('id-ID').format(angka);
}

function addToCart(event, id, name, price) {
    event.preventDefault();
    event.stopPropagation();
    
    console.log('=== ADD TO CART ===');
    console.log('ID:', id, 'Name:', name, 'Price:', price);
    
    const formData = new FormData();
    formData.append('id', id);
    formData.append('name', name);
    formData.append('price', price);
    formData.append('qty', 1);

    fetch('index.php?page=add_cart', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log('Raw Response:', text);
        
        try {
            const data = JSON.parse(text);
            console.log('Parsed Data:', data);
            
            if (data.success) {
                updateCartBadge(data.cart_count);
                showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
            } else {
                showNotification(data.message || 'Gagal menambahkan produk', 'error');
            }
        } catch (e) {
            console.error('JSON Parse Error:', e);
            showNotification('Server error', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    });
}

function updateQty(productId, action) {
    const qtyInput = document.getElementById(`qty-${productId}`);
    let currentQty = parseInt(qtyInput.value);

    if (action === 'increase') {
        currentQty += 1;
    } else if (action === 'decrease') {
        if (currentQty > 1) {
            currentQty -= 1;
        } else {
            return;
        }
    } else if (action === 'manual') {
        currentQty = parseInt(qtyInput.value);
        if (currentQty < 1 || isNaN(currentQty)) {
            currentQty = 1;
        }
    }

    qtyInput.value = currentQty;
    updateRowTotal(productId, currentQty);
    calculateGrandTotal();
    sendUpdateToServer(productId, currentQty);
}

function updateRowTotal(productId, qty) {
    const row = document.getElementById(`row-${productId}`);
    if (!row) return;

    const unitPriceElement = row.querySelector('.unit-price');
    if (!unitPriceElement) return;

    const unitPrice = parseInt(unitPriceElement.getAttribute('data-price'));
    const newRowTotal = unitPrice * qty;

    const totalElement = document.getElementById(`total-${productId}`);
    if (totalElement) {
        totalElement.innerText = formatRupiah(newRowTotal);
    }
}

function calculateGrandTotal() {
    let grandTotal = 0;
    const allRows = document.querySelectorAll('tbody tr');

    allRows.forEach(row => {
        const priceEl = row.querySelector('.unit-price');
        const qtyEl = row.querySelector('.quantity-amount');

        if (priceEl && qtyEl) {
            const price = parseInt(priceEl.getAttribute('data-price'));
            const qty = parseInt(qtyEl.value);
            grandTotal += (price * qty);
        }
    });

    const grandTotalEl = document.getElementById('grand-total');
    if (grandTotalEl) {
        grandTotalEl.innerText = formatRupiah(grandTotal);
    }

    const modalTotalEl = document.getElementById('modal-total-display');
    if (modalTotalEl) {
        modalTotalEl.innerText = formatRupiah(grandTotal);
    }

    updateCartBadgeFromCart();
}

function sendUpdateToServer(id, qty) {
    const formData = new FormData();
    formData.append('id_product', id);
    formData.append('qty', qty);

    fetch('index.php?page=update_cart', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Cart updated successfully');
        } else {
            console.error('Failed to update cart:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// PERBAIKAN: Update Cart Badge dengan selector yang lebih fleksibel
function updateCartBadge(count) {
    console.log('Updating badge to:', count);
    
    // Cari cart link dengan berbagai selector
    const cartLink = document.getElementById('cart-link') || 
                     document.querySelector('a[href*="cart"]') ||
                     document.querySelector('.position-relative');
    
    if (!cartLink) {
        console.error('Cart link not found!');
        return;
    }
    
    let badge = cartLink.querySelector('.cart-badge');
    
    if (count > 0) {
        if (badge) {
            // Badge sudah ada, update
            badge.textContent = count;
            badge.classList.add('updated');
            setTimeout(() => badge.classList.remove('updated'), 300);
        } else {
            // Buat badge baru
            badge = document.createElement('span');
            badge.className = 'cart-badge';
            badge.textContent = count;
            cartLink.appendChild(badge);
            console.log('Badge created with count:', count);
        }
    } else if (badge) {
        // Hapus badge jika count = 0
        badge.remove();
    }
}

function updateCartBadgeFromCart() {
    let totalQty = 0;
    document.querySelectorAll('.quantity-amount').forEach(input => {
        totalQty += parseInt(input.value) || 0;
    });
    console.log('Total quantity from cart page:', totalQty);
    updateCartBadge(totalQty);
}

// PERBAIKAN: Load Badge on Page Load
function loadCartBadge() {
    console.log('Loading cart badge...');
    
    fetch('index.php?page=get_cart_count')
        .then(response => response.json())
        .then(data => {
            console.log('Cart count response:', data);
            if (data.success) {
                updateCartBadge(data.cart_count);
            }
        })
        .catch(error => {
            console.error('Failed to load cart count:', error);
        });
}

// PERBAIKAN: Show Notification
function showNotification(message, type = 'success') {
    // Hapus notifikasi lama
    document.querySelectorAll('.notification').forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
    `;
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 9999;
        font-weight: 500;
        display: flex;
        align-items: center;
        animation: slideIn 0.3s ease;
        min-width: 250px;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// CSS Animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { 
            transform: translateX(400px); 
            opacity: 0; 
        }
        to { 
            transform: translateX(0); 
            opacity: 1; 
        }
    }
    
    @keyframes slideOut {
        from { 
            transform: translateX(0); 
            opacity: 1; 
        }
        to { 
            transform: translateX(400px); 
            opacity: 0; 
        }
    }
    
    .cart-badge {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        background-color: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: bold;
        border-radius: 50%;
        position: absolute;
        top: -8px;
        right: -10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        z-index: 10;
    }
    
    .cart-badge.updated {
        animation: badgePulse 0.3s ease;
    }
    
    @keyframes badgePulse {
        0%, 100% { 
            transform: scale(1); 
        }
        50% { 
            transform: scale(1.3); 
        }
    }
    
    .position-relative {
        position: relative !important;
        display: inline-block;
    }
`;
document.head.appendChild(style);

// Initialize on Page Load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing cart...');
    
    // Load cart badge on every page
    loadCartBadge();
    
    // If on cart page, calculate grand total
    if (document.querySelector('.site-blocks-table')) { 
        calculateGrandTotal();
    }
});