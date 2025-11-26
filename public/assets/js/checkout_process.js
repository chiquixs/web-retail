// File: public/assets/js/checkout_process.js

const checkoutForm = document.getElementById('checkoutForm');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault(); 

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        submitBtn.disabled = true;

        // PERUBAHAN KRITIS: Targetkan rute MVC baru
        fetch('index.php?page=checkout_process', { // Ganti dari 'checkout_process.php'
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Arahkan ke rute yang dikirim dari controller: index.php?page=thankyou
                window.location.href = data.redirect || 'index.php?page=thankyou'; 
            } else {
                alert("Error: " + data.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Terjadi kesalahan koneksi.");
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // File: public/assets/js/checkout_process.js

document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            
            // Get form data
            const formData = new FormData(this);
            
            // Send AJAX request
            fetch('index.php?page=checkout', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', data.message);
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
                    modal.hide();
                    
                    // Reset form
                    checkoutForm.reset();
                    
                    // Update cart badge to 0
                    updateCartBadge(0);
                    
                    // Redirect to home after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'index.php?page=home';
                    }, 2000);
                } else {
                    // Show error message
                    showAlert('error', data.message);
                    
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

// Show alert notification
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
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Update cart badge (reuse from cart_logic.js)
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
}