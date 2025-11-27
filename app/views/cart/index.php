<?php include '../app/views/includes/header.php'; ?>

<div class="untree_co-section before-footer-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="site-blocks-table">
                    <table class="table">
                        <tbody>
                            <?php if (empty($data['cartItems'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Keranjang Anda kosong</p>
                                        <a href="index.php?page=shop" class="btn btn-primary mt-3">Belanja Sekarang</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['cartItems'] as $item): ?>
                                    <tr id="row-<?= $item['id_product'] ?>">
                                        <td class="product-thumbnail">
                                            <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                 class="img-fluid" 
                                                 style="max-height: 80px; max-width: 80px;">
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h6 text-black"><?= htmlspecialchars($item['name']) ?></h2>
                                        </td>
                                        <td class="product-price">
                                            Rp<?= number_format($item['price'], 0, ',', '.') ?>
                                            <span class="unit-price d-none" data-price="<?= $item['price'] ?>"></span>
                                        </td>
                                        <td>
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="updateQty(<?= $item['id_product'] ?>, 'decrease')">-</button>
                                                
                                                <input type="number"
                                                    id="qty-<?= $item['id_product'] ?>"
                                                    class="form-control text-center quantity-amount"
                                                    value="<?= $item['qty'] ?>"
                                                    min="1"
                                                    onchange="updateQty(<?= $item['id_product'] ?>, 'manual')">
                                                
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="updateQty(<?= $item['id_product'] ?>, 'increase')">+</button>
                                            </div>
                                        </td>
                                        <td class="product-total" id="total-<?= $item['id_product'] ?>">
                                            Rp<?= number_format($item['subtotal'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <a href="index.php?page=remove_cart&id=<?= $item['id_product'] ?>" 
                                               class="btn btn-black btn-sm"
                                               onclick="return confirm('Hapus produk ini dari keranjang?')">X</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (!empty($data['cartItems'])): ?>
        <div class="row justify-content-end">
            <div class="col-md-6">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <span class="text-black">Grand Total</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong class="text-black" id="grand-total">Rp<?= number_format($data['totalAmount'], 0, ',', '.') ?></strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button"
                            class="btn btn-black btn-lg py-3 w-100"
                            style="background-color: #3b5d50; border-color: #3b5d50;"
                            data-bs-toggle="modal"
                            data-bs-target="#checkoutModal">
                            Proceed To Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Checkout Modal -->
        <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutModalLabel">Informasi Checkout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="checkoutForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="c_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_name" name="name" required 
                                       placeholder="Masukkan nama lengkap Anda">
                            </div>

                            <div class="mb-3">
                                <label for="c_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="c_email" name="email" required 
                                       placeholder="nama@example.com">
                            </div>

                            <div class="mb-3">
                                <label for="c_address" class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="c_address" name="address" rows="3" required 
                                          placeholder="Masukkan alamat lengkap Anda"></textarea>
                            </div>

                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <span>Total Pembayaran:</span>
                                <strong id="modal-total-display">Rp<?= number_format($data['totalAmount'] ?? 0, 0, ',', '.') ?></strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" 
                                    style="background-color: #3b5d50; border-color: #3b5d50;">
                                Konfirmasi & Bayar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ===== CART FUNCTIONS =====

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
        if (data.success) {
            console.log('Cart updated successfully');
        } else {
            console.error('Failed to update cart:', data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCartBadge(count) {
    const cartLink = document.getElementById('cart-link') || 
                     document.querySelector('a[href*="cart"]');
    
    if (!cartLink) return;
    
    let badge = cartLink.querySelector('.cart-badge');
    
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            badge = document.createElement('span');
            badge.className = 'cart-badge';
            badge.textContent = count;
            cartLink.appendChild(badge);
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

let isCheckoutInProgress = false; // Flag to prevent multiple submits

document.addEventListener('DOMContentLoaded', function() {
    calculateGrandTotal();
    
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Prevent double submit
            if (isCheckoutInProgress) {
                console.log('Checkout already in progress');
                return;
            }
            
            isCheckoutInProgress = true;
            console.log('=== CHECKOUT STARTED ===');
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            
            const formData = new FormData(this);
            
            fetch('index.php?page=checkout', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text.substring(0, 200));
                
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Invalid JSON response');
                }
                
                console.log('Parsed data:', data);
                
                if (data.success) {
                    console.log('=== CHECKOUT SUCCESS ===');
                    
                    // Show success message
                    showSuccessMessage(data.message);
                    
                    // Close modal immediately
                    const modalElement = document.getElementById('checkoutModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    
                    // Remove modal backdrop
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('overflow');
                    document.body.style.removeProperty('padding-right');
                    
                    // Update badge to 0 immediately
                    updateCartBadge(0);
                    
                    // Redirect after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = 'index.php?page=home';
                    }, 1500);
                    
                } else {
                    console.error('=== CHECKOUT FAILED ===');
                    console.error('Error:', data.message);
                    
                    showErrorMessage(data.message);
                    
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    isCheckoutInProgress = false;
                }
            })
            .catch(error => {
                console.error('=== CHECKOUT ERROR ===');
                console.error('Error:', error);
                
                showErrorMessage('Terjadi kesalahan: ' + error.message);
                
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                isCheckoutInProgress = false;
            });
        });
    }
});

function showSuccessMessage(message) {
    document.querySelectorAll('.checkout-alert').forEach(el => el.remove());
    
    const alert = document.createElement('div');
    alert.className = 'alert alert-success checkout-alert';
    alert.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 10000;
        min-width: 350px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        border-left: 4px solid #10b981;
        animation: slideIn 0.3s ease;
    `;
    alert.innerHTML = `
        <i class="fas fa-check-circle me-3" style="font-size: 24px; color: #10b981;"></i>
        <span style="font-size: 15px; font-weight: 500;">${message}</span>
    `;
    
    document.body.appendChild(alert);
}

function showErrorMessage(message) {
    document.querySelectorAll('.checkout-alert').forEach(el => el.remove());
    
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger checkout-alert';
    alert.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 10000;
        min-width: 350px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        border-left: 4px solid #ef4444;
        animation: slideIn 0.3s ease;
    `;
    alert.innerHTML = `
        <i class="fas fa-exclamation-circle me-3" style="font-size: 24px; color: #ef4444;"></i>
        <span style="font-size: 15px; font-weight: 500;">${message}</span>
        <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(alert);
}

// Add CSS animation
const checkoutStyle = document.createElement('style');
checkoutStyle.textContent = `
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
`;
document.head.appendChild(checkoutStyle);
</script>
<?php include '../app/views/includes/footer.php'; ?>