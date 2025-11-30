

<?php
// File: app/views/cart/thankyou.php
include '../app/views/includes/header.php';

// Get success message from session
$message = isset($_SESSION['checkout_message']) ? $_SESSION['checkout_message'] : 'Transaksi berhasil!';
$customerId = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

// Clear the session flags after displaying
unset($_SESSION['checkout_success']);
unset($_SESSION['checkout_message']);
unset($_SESSION['customer_id']);
?>

<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <!-- Success Icon -->
                <div class="mb-4">
                    <div class="success-checkmark">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="icon-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <h2 class="display-4 text-black mb-4">Terima Kasih! 🎉</h2>
                <p class="lead mb-4"><?= htmlspecialchars($message) ?></p>

                <!-- Order Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Pesanan Anda Berhasil Diproses</h5>
                        <p class="text-muted mb-3">
                            Kami telah menerima pesanan Anda dan akan segera memprosesnya. 
                            Anda akan menerima email konfirmasi segera.
                        </p>
                        <?php if ($customerId): ?>
                        <p class="text-muted small mb-0">
                            <strong>Customer ID:</strong> #<?= str_pad($customerId, 6, '0', STR_PAD_LEFT) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                    <a href="index.php?page=home" class="btn btn-black btn-lg px-5">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                    <a href="index.php?page=shop" class="btn btn-outline-black btn-lg px-5">
                        <i class="fas fa-shopping-bag me-2"></i>Belanja Lagi
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-5 p-4 bg-light rounded">
                    <h6 class="text-black mb-3">Langkah Selanjutnya</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                            <p class="small mb-0">Cek email untuk konfirmasi pesanan</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                            <p class="small mb-0">Pesanan akan diproses dalam 1-2 hari kerja</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-headset fa-2x text-primary mb-2"></i>
                            <p class="small mb-0">Hubungi kami jika ada pertanyaan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Success Checkmark Animation */
.success-checkmark {
    width: 80px;
    height: 80px;
    margin: 0 auto;
}

.success-checkmark .check-icon {
    width: 80px;
    height: 80px;
    position: relative;
    border-radius: 50%;
    box-sizing: content-box;
    border: 4px solid #4CAF50;
}

.success-checkmark .check-icon::before {
    top: 3px;
    left: -2px;
    width: 30px;
    transform-origin: 100% 50%;
    border-radius: 100px 0 0 100px;
}

.success-checkmark .check-icon::after {
    top: 0;
    left: 30px;
    width: 60px;
    transform-origin: 0 50%;
    border-radius: 0 100px 100px 0;
    animation: rotate-circle 4.25s ease-in;
}

.success-checkmark .check-icon::before, 
.success-checkmark .check-icon::after {
    content: '';
    height: 100px;
    position: absolute;
    background: #FFFFFF;
    transform: rotate(-45deg);
}

.success-checkmark .check-icon .icon-line {
    height: 5px;
    background-color: #4CAF50;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}

.success-checkmark .check-icon .icon-line.line-tip {
    top: 46px;
    left: 14px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}

.success-checkmark .check-icon .icon-line.line-long {
    top: 38px;
    right: 8px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

.success-checkmark .check-icon .icon-circle {
    top: -4px;
    left: -4px;
    z-index: 10;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    position: absolute;
    box-sizing: content-box;
    border: 4px solid rgba(76, 175, 80, .5);
}

.success-checkmark .check-icon .icon-fix {
    top: 8px;
    width: 5px;
    left: 26px;
    z-index: 1;
    height: 85px;
    position: absolute;
    transform: rotate(-45deg);
    background-color: #FFFFFF;
}

@keyframes rotate-circle {
    0% {
        transform: rotate(-45deg);
    }
    5% {
        transform: rotate(-45deg);
    }
    12% {
        transform: rotate(-405deg);
    }
    100% {
        transform: rotate(-405deg);
    }
}

@keyframes icon-line-tip {
    0% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    54% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    70% {
        width: 50px;
        left: -8px;
        top: 37px;
    }
    84% {
        width: 17px;
        left: 21px;
        top: 48px;
    }
    100% {
        width: 25px;
        left: 14px;
        top: 45px;
    }
}

@keyframes icon-line-long {
    0% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    65% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    84% {
        width: 55px;
        right: 0px;
        top: 35px;
    }
    100% {
        width: 47px;
        right: 8px;
        top: 38px;
    }
}

/* Button Styles */
.btn-black {
    background-color: #3b5d50;
    border-color: #3b5d50;
    color: white;
}

.btn-black:hover {
    background-color: #2d4a3e;
    border-color: #2d4a3e;
    color: white;
}

.btn-outline-black {
    border: 2px solid #3b5d50;
    color: #3b5d50;
    background: transparent;
}

.btn-outline-black:hover {
    background-color: #3b5d50;
    color: white;
}

.card {
    border: none;
    border-radius: 10px;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<script>
// Auto-refresh cart badge to 0
document.addEventListener('DOMContentLoaded', function() {
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.remove();
    }
});
</script>

<?php include '../app/views/includes/footer.php'; ?>