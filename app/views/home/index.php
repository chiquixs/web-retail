<?php include '../app/views/includes/header.php'; ?>

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Modern Interior <span class="d-block">Home Furniture</span></h1>
                    <p class="mb-4">Discover a curated collection of stylish, functional, and high-quality home furniture designed to elevate every room in your living space. From cozy sofas and elegant dining sets to modern storage solutions and bedroom essentials, we bring you pieces that blend comfort, durability, and timeless design.</p>
                    <p>
                        <a href="index.php?page=shop" class="btn btn-secondary me-2">Shop Now</a>
                        <a href="#products" class="btn btn-white-outline">Explore</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="hero-img-wrap">
                    <img src="assets/images/couch.png" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<!-- Start Product Section -->
<div class="product-section" id="products">
    <div class="container">
        <div class="row">
            <!-- Start Column 1 -->
            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Best Selling Products</h2>
                <p class="mb-4">Check out our most popular furniture pieces loved by our customers. These top-rated items combine style, quality, and functionality.</p>
                <p><a href="index.php?page=shop" class="btn">View All Products</a></p>
            </div> 
            <!-- End Column 1 -->

            <?php if (!empty($data['topProducts'])): ?>
                <?php foreach ($data['topProducts'] as $product): ?>
                    <!-- Product Item -->
                    <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                        <div class="product-item" style="position: relative;">
                            <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                                 class="img-fluid product-thumbnail" 
                                 alt="<?= htmlspecialchars($product['name']) ?>">
                            <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                            <strong class="product-price">Rp<?= number_format($product['price'], 0, ',', '.') ?></strong>

                            <?php if ($product['total_sold'] > 0): ?>
                                <span class="badge bg-success" style="position: absolute; top: 10px; left: 10px;">
                                    🔥 <?= $product['total_sold'] ?> Sold
                                </span>
                            <?php endif; ?>

                            <!-- Link to Shop Instead of Add to Cart -->
                            <a href="index.php?page=shop" class="icon-cross" title="View in Shop">
                                <img src="assets/images/cross.svg" class="img-fluid">
                            </a>
                        </div>
                    </div>
                    <!-- End Product Item -->
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 col-lg-9 text-center">
                    <p class="text-muted">No products available at the moment.</p>
                    <a href="index.php?page=shop" class="btn btn-secondary">Browse Shop</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- End Product Section -->

<!-- Start Why Choose Us Section -->
<div class="why-choose-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6">
                <h2 class="section-title">Why Choose Us</h2>
                <p>We provide the best furniture shopping experience with quality products, excellent service, and customer satisfaction guaranteed.</p>

                <div class="row my-5">
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="assets/images/truck.svg" alt="Fast Shipping" class="imf-fluid">
                            </div>
                            <h3>Fast &amp; Free Shipping</h3>
                            <p>Get your furniture delivered quickly and safely to your doorstep at no extra cost.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="assets/images/bag.svg" alt="Easy Shopping" class="imf-fluid">
                            </div>
                            <h3>Easy to Shop</h3>
                            <p>Browse our extensive catalog and find exactly what you need with our user-friendly interface.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="assets/images/support.svg" alt="24/7 Support" class="imf-fluid">
                            </div>
                            <h3>24/7 Support</h3>
                            <p>Our customer service team is always ready to help you with any questions or concerns.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="assets/images/return.svg" alt="Easy Returns" class="imf-fluid">
                            </div>
                            <h3>Hassle Free Returns</h3>
                            <p>Not satisfied? Return your purchase easily within 30 days for a full refund.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="img-wrap">
                    <img src="assets/images/why-choose-us-img.jpg" alt="Why Choose Us" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Why Choose Us Section -->

<!-- Start Popular Product (Optional: You can remove this or make it dynamic too) -->
<div class="popular-product">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Ready to Start Shopping?</h2>
                <p class="lead mb-4">Explore our full collection of quality furniture</p>
                <a href="index.php?page=shop" class="btn btn-secondary btn-lg">Visit Shop</a>
            </div>
        </div>
    </div>
</div>
<!-- End Popular Product -->

<style>
/* Additional styles for home page */
.product-item {
    cursor: default;
    transition: transform 0.3s ease;
}

.product-item:hover {
    transform: translateY(-5px);
}

.icon-cross {
    cursor: pointer;
    transition: all 0.3s ease;
}

.icon-cross:hover {
    transform: scale(1.2) rotate(90deg);
}

.badge {
    font-size: 11px !important;
    padding: 6px 10px !important;
    font-weight: 600 !important;
}

.section-title {
    color: #2f2f2f;
}

.btn-secondary {
    background-color: #3b5d50;
    border-color: #3b5d50;
}

.btn-secondary:hover {
    background-color: #2d4a3e;
    border-color: #2d4a3e;
}
</style>

<?php include '../app/views/includes/footer.php'; ?>