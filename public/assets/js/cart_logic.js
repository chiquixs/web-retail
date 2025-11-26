// ===== CART LOGIC - Complete Version =====

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
    .then(response => {
        console.log('Response Status:', response.status);
        console.log('Response Headers:', response.headers.get('content-type'));
        
        // Clone response to read it twice
        return response.text().then(text => {
            console.log('Raw Response:', text);
            
            // Check if response is JSON
            if (!response.headers.get('content-type')?.includes('application/json')) {
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
            }
            
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Failed to parse JSON: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        console.log('Parsed Data:', data);
        
        if (data.success) {
            updateCartBadge(data.cart_count);
            showNotification('Product added to cart!', 'success');
        } else {
            showNotification(data.message || 'Failed to add product', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error: ' + error.message, 'error');
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
        if (!data.success) {
            console.error('Failed to update cart:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
            badge.classList.add('updated');
            setTimeout(() => badge.classList.remove('updated'), 300);
        } else {
            const cartIcon = document.querySelector('a[href*="cart"]');
            if (cartIcon) {
                const newBadge = document.createElement('span');
                newBadge.className = 'cart-badge updated';
                newBadge.textContent = count;
                cartIcon.appendChild(newBadge);
            }
        }
    } else if (badge) {
        badge.remove();
    }
}

function updateCartBadgeFromCart() {
    let totalQty = 0;
    document.querySelectorAll('.quantity-amount').forEach(input => {
        totalQty += parseInt(input.value) || 0;
    });
    updateCartBadge(totalQty);
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
    
    .cart-badge.updated {
        animation: badgePulse 0.3s ease;
    }
    
    @keyframes badgePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
`;
document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.site-blocks-table')) { 
        calculateGrandTotal();
    }
});


function formatRupiah(angka) {
    return 'Rp' + new Intl.NumberFormat('id-ID').format(angka);
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
        if (!data.success) {
            console.error('Failed to update cart:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        }
    } else if (badge) {
        badge.remove();
    }
}

function updateCartBadgeFromCart() {
    let totalQty = 0;
    document.querySelectorAll('.quantity-amount').forEach(input => {
        totalQty += parseInt(input.value) || 0;
    });
    updateCartBadge(totalQty);
}

// ===== CHECKOUT FORM HANDLER =====

document.addEventListener('DOMContentLoaded', function() {
    calculateGrandTotal();
    
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            
            const formData = new FormData(this);
            
            fetch('index.php?page=checkout', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
                    modal.hide();
                    
                    checkoutForm.reset();
                    updateCartBadge(0);
                    
                    setTimeout(() => {
                        window.location.href = 'index.php?page=home';
                    }, 2000);
                } else {
                    showAlert('error', data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

