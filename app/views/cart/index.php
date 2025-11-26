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

<!-- Cart & Checkout JavaScript -->
<script>
// ===== CART FUNCTIONS =====

</script>

<?php include '../app/views/includes/footer.php'; ?>