<?php include '../app/views/includes/header.php'; ?>

<style>
/* ===== CART PAGE CRITICAL STYLES ===== */
/* Override semua style yang mungkin bentrok */

.untree_co-section {
    padding: 50px 0;
}

.cart-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 30px;
    background: white;
    table-layout: fixed !important; /* CRITICAL: Fix column width */
}

/* Table Header */
.cart-table thead th {
    background-color: #3b5d50 !important;
    color: white !important;
    padding: 18px 15px !important;
    text-align: left !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    border: none !important;
    vertical-align: middle !important;
}

/* Column Width - CRITICAL */
.cart-table thead th:nth-child(1) { width: 100px !important; } /* Image */
.cart-table thead th:nth-child(2) { width: 25% !important; }   /* Product */
.cart-table thead th:nth-child(3) { width: 15% !important; }   /* Price */
.cart-table thead th:nth-child(4) { width: 20% !important; }   /* Quantity */
.cart-table thead th:nth-child(5) { width: 15% !important; }   /* Total */
.cart-table thead th:nth-child(6) { width: 80px !important; }  /* Remove */

/* Table Body */
.cart-table tbody td {
    padding: 25px 15px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #e9ecef !important;
    font-size: 15px !important;
}

.cart-table tbody tr:last-child td {
    border-bottom: none !important;
}

/* Product Thumbnail */
.product-thumbnail {
    text-align: center !important;
}

.product-thumbnail img {
    width: 70px !important;
    height: 70px !important;
    object-fit: cover !important;
    border-radius: 8px !important;
    display: block !important;
    margin: 0 auto !important;
}

/* Product Name */
.product-name {
    font-weight: 600 !important;
    color: #2f2f2f !important;
    font-size: 16px !important;
}

/* Product Price */
.product-price {
    font-weight: 600 !important;
    color: #2f2f2f !important;
    font-size: 15px !important;
}

/* Quantity Controls */
.quantity-controls {
    display: inline-flex !important;
    align-items: center !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 6px !important;
    overflow: hidden !important;
    background: white !important;
}

.qty-btn {
    width: 35px !important;
    height: 40px !important;
    border: none !important;
    background: #f8f9fa !important;
    color: #2f2f2f !important;
    font-size: 18px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
}

.qty-btn:hover {
    background: #e9ecef !important;
}

.qty-input {
    width: 50px !important;
    height: 40px !important;
    border: none !important;
    border-left: 1px solid #dee2e6 !important;
    border-right: 1px solid #dee2e6 !important;
    text-align: center !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #2f2f2f !important;
    padding: 0 !important;
}

.qty-input:focus {
    outline: none !important;
    box-shadow: none !important;
}

/* Remove button "X" */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
}

input[type="number"] {
    -moz-appearance: textfield !important;
}

/* Product Total */
.product-total {
    font-weight: 700 !important;
    color: #2f2f2f !important;
    font-size: 16px !important;
}

/* Remove Button */
.remove-btn {
    display: inline-block !important;
    width: 32px !important;
    height: 32px !important;
    line-height: 30px !important;
    text-align: center !important;
    background: #dc3545 !important;
    color: white !important;
    border-radius: 4px !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    transition: all 0.3s !important;
}

.remove-btn:hover {
    background: #c82333 !important;
    color: white !important;
    transform: scale(1.1) !important;
}

/* Cart Summary Box */
.cart-summary {
    background: #f8f9fa !important;
    padding: 30px !important;
    border-radius: 8px !important;
    margin-left: auto !important;
    max-width: 400px !important;
}

.cart-summary h3 {
    font-size: 18px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    margin-bottom: 20px !important;
    padding-bottom: 15px !important;
    border-bottom: 2px solid #2f2f2f !important;
}

.summary-row {
    display: flex !important;
    justify-content: space-between !important;
    margin-bottom: 20px !important;
    font-size: 16px !important;
}

.summary-row span:first-child {
    color: #666 !important;
}

.summary-row span:last-child {
    font-weight: 700 !important;
    color: #2f2f2f !important;
    font-size: 18px !important;
}

/* Buttons */
.btn-black {
    background-color: #2f2f2f !important;
    border: 2px solid #2f2f2f !important;
    color: white !important;
    padding: 15px 40px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    border-radius: 50px !important;
    text-transform: capitalize !important;
    transition: all 0.3s ease !important;
    display: inline-block !important;
    text-decoration: none !important;
    text-align: center !important;
    width: 100% !important;
}

.btn-black:hover {
    background-color: #1a1a1a !important;
    border-color: #1a1a1a !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
    color: white !important;
}

.btn-outline-dark {
    background: transparent !important;
    border: 2px solid #2f2f2f !important;
    color: #2f2f2f !important;
    padding: 15px 40px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    border-radius: 50px !important;
    text-transform: capitalize !important;
    transition: all 0.3s ease !important;
    display: inline-block !important;
    text-decoration: none !important;
}

.btn-outline-dark:hover {
    background-color: #2f2f2f !important;
    color: white !important;
}

/* Responsive */
@media (max-width: 768px) {
    .cart-table {
        font-size: 13px !important;
    }
    
    .cart-table thead th {
        padding: 12px 8px !important;
        font-size: 12px !important;
    }
    
    .cart-table tbody td {
        padding: 15px 8px !important;
    }
    
    .product-thumbnail img {
        width: 50px !important;
        height: 50px !important;
    }
    
    .qty-input {
        width: 40px !important;
    }
}
</style>

<div class="untree_co-section before-footer-section">
    <div class="container">
        <?php if (empty($data['cartItems'])): ?>
            <div class="row">
                <div class="col-md-12 text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Keranjang Anda kosong</p>
                    <a href="index.php?page=shop" class="btn btn-black mt-3">Belanja Sekarang</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Cart Table -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>IMAGE</th>
                                    <th>PRODUCT</th>
                                    <th>PRICE</th>
                                    <th>QUANTITY</th>
                                    <th>TOTAL</th>
                                    <th>REMOVE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['cartItems'] as $item): ?>
                                    <tr id="row-<?= $item['id_product'] ?>">
                                        <td class="product-thumbnail">
                                            <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>">
                                        </td>
                                        <td class="product-name">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </td>
                                        <td class="product-price">
                                            <span class="unit-price" data-price="<?= $item['price'] ?>">
                                                Rp<?= number_format($item['price'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="product-quantity">
                                            <div class="quantity-controls">
                                                <button type="button" class="qty-btn" 
                                                        onclick="updateQty(<?= $item['id_product'] ?>, 'decrease')">−</button>
                                                <input type="number"
                                                       id="qty-<?= $item['id_product'] ?>"
                                                       class="qty-input quantity-amount"
                                                       value="<?= $item['qty'] ?>"
                                                       min="1"
                                                       onchange="updateQty(<?= $item['id_product'] ?>, 'manual')">
                                                <button type="button" class="qty-btn"
                                                        onclick="updateQty(<?= $item['id_product'] ?>, 'increase')">+</button>
                                            </div>
                                        </td>
                                        <td class="product-total" id="total-<?= $item['id_product'] ?>">
                                            Rp<?= number_format($item['subtotal'], 0, ',', '.') ?>
                                        </td>
                                        <td class="product-remove">
                                            <a href="index.php?page=remove_cart&id=<?= $item['id_product'] ?>" 
                                               class="remove-btn"
                                               onclick="return confirm('Hapus produk ini dari keranjang?')">X</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="row">
                <div class="col-md-6">
                    <a href="index.php?page=shop" class="btn btn-outline-dark btn-lg">
                        Continue Shopping
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="cart-summary">
                        <h3>CART TOTALS</h3>
                        <div class="summary-row">
                            <span>Total</span>
                            <span id="grand-total">Rp<?= number_format($data['totalAmount'], 0, ',', '.') ?></span>
                        </div>
                        <button type="button"
                                class="btn btn-black"
                                data-bs-toggle="modal"
                                data-bs-target="#checkoutModal">
                            Proceed To Checkout
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Checkout Modal -->
        <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informasi Checkout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="checkoutForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="c_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="c_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="c_email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="c_address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="c_address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="alert alert-info d-flex justify-content-between">
                                <span>Total Pembayaran:</span>
                                <strong id="modal-total-display">Rp<?= number_format($data['totalAmount'] ?? 0, 0, ',', '.') ?></strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-black">Konfirmasi & Bayar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function formatRupiah(angka) {
    return 'Rp' + new Intl.NumberFormat('id-ID').format(angka);
}

function updateQty(productId, action) {
    const qtyInput = document.getElementById(`qty-${productId}`);
    let currentQty = parseInt(qtyInput.value);

    if (action === 'increase') {
        currentQty += 1;
    } else if (action === 'decrease') {
        if (currentQty > 1) currentQty -= 1;
        else return;
    } else if (action === 'manual') {
        currentQty = parseInt(qtyInput.value);
        if (currentQty < 1 || isNaN(currentQty)) currentQty = 1;
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
            console.log('Cart updated');
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

document.addEventListener('DOMContentLoaded', function() {
    calculateGrandTotal();
});
</script>

<?php include '../app/views/includes/footer.php'; ?>