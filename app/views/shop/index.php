<?php include '../app/views/includes/header.php'; ?>

<div class="hero" style="padding-top: 3rem; padding-bottom: 1rem;">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt" >
                    <!-- <h1 style="font-size: 40px;">Shop</h1> -->
                    <p class="mb-2" style="font-size: 17px;">Browse our collection of quality furniture</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-4" style="text-align: center;" >Our Products</h2>
            </div>
        </div>

        <div class="row g-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $item):
                    $price = $item['price'] ?? 0;
                    $imageName = $item['image'] ?? 'product-placeholder.png';
                ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <a class="product-item" href="index.php?page=product_detail&id=<?= $item['id_product'] ?>">
                                <div class="product-img-wrap">
                                    <img src="assets/images/products/<?= htmlspecialchars($imageName) ?>"
                                        class="product-image"
                                        alt="<?= htmlspecialchars($item['name']) ?>"
                                        onerror="this.onerror=null; this.src='assets/images/product-placeholder.png';">
                                </div>
                                <h3 class="product-title"><?= htmlspecialchars($item['name']) ?></h3>
                                <strong class="product-price">Rp<?= number_format($price, 0, ',', '.') ?></strong>
                            </a>
                            <button onclick="addToCart(event, 
                                <?= $item['id_product'] ?>, 
                                '<?= htmlspecialchars(addslashes($item['name'])) ?>', 
                                <?= $price ?>
                            )" class="btn btn-primary">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 py-5 text-center">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada produk.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../app/views/includes/footer.php'; ?>