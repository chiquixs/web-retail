// File: public/assets/js/checkout_process.js
// ✅ CORRECTED: Using checkout_process endpoint

let isCheckoutInProgress = false;

document.addEventListener('DOMContentLoaded', function() {
    console.log('======================');
    console.log('✅ checkout_process.js loaded');
    console.log('Current URL:', window.location.href);
    console.log('======================');
    
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (!checkoutForm) {
        console.log('⚠️ No checkout form found on this page');
        return;
    }
    
    console.log('✅ Checkout form found, attaching listener');
    console.log('Form ID:', checkoutForm.id);
    
    // ✅ CRITICAL: Only ONE event listener
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Prevent double submit
        if (isCheckoutInProgress) {
            console.warn('⚠️ Checkout already in progress, ignoring...');
            return;
        }
        
        isCheckoutInProgress = true;
        console.log('=== 🛒 CHECKOUT STARTED ===');
        console.log('Timestamp:', new Date().toISOString());
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Disable button immediately
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        
        const formData = new FormData(this);
        
        // Log form data for debugging
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }
        
        // ✅ Send to checkout_process endpoint
        console.log('📤 Sending request to: index.php?page=checkout_process');
        
        fetch('index.php?page=checkout_process', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('📥 Response received');
            console.log('Status:', response.status);
            console.log('OK:', response.ok);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            console.log('📄 Raw response (first 500 chars):');
            console.log(text.substring(0, 500));
            
            let data;
            try {
                data = JSON.parse(text);
                console.log('✅ JSON parsed successfully');
            } catch (e) {
                console.error('❌ JSON parse error:', e);
                console.error('Full response:', text);
                throw new Error('Invalid JSON response from server');
            }
            
            console.log('📦 Parsed data:', data);
            
            if (data.success) {
                console.log('=== ✅ CHECKOUT SUCCESS ===');
                
                // Show success message
                showSuccessMessage(data.message);
                
                // Close modal
                const modalElement = document.getElementById('checkoutModal');
                if (modalElement) {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
                
                // Clean up modal backdrop
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                
                // Update cart badge to 0
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge(0);
                }
                
                // Redirect
                console.log('🔄 Redirecting to home in 1.5 seconds...');
                setTimeout(() => {
                    window.location.href = 'index.php?page=home';
                }, 1500);
                
            } else {
                console.error('=== ❌ CHECKOUT FAILED ===');
                console.error('Error message:', data.message);
                
                showErrorMessage(data.message);
                resetButton(submitBtn, originalText);
                isCheckoutInProgress = false;
            }
        })
        .catch(error => {
            console.error('=== ❌ CHECKOUT ERROR ===');
            console.error('Error:', error);
            console.error('Stack:', error.stack);
            
            showErrorMessage('Terjadi kesalahan: ' + error.message);
            resetButton(submitBtn, originalText);
            isCheckoutInProgress = false;
        });
    });
});

function resetButton(btn, originalText) {
    btn.disabled = false;
    btn.innerHTML = originalText;
}

function showSuccessMessage(message) {
    removeAllAlerts();
    
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
        background: white;
    `;
    alert.innerHTML = `
        <i class="fas fa-check-circle me-3" style="font-size: 24px; color: #10b981;"></i>
        <span style="font-size: 15px; font-weight: 500;">${message}</span>
    `;
    
    document.body.appendChild(alert);
}

function showErrorMessage(message) {
    removeAllAlerts();
    
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
        background: white;
    `;
    alert.innerHTML = `
        <i class="fas fa-exclamation-circle me-3" style="font-size: 24px; color: #ef4444;"></i>
        <span style="font-size: 15px; font-weight: 500;">${message}</span>
        <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alert.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
}

function removeAllAlerts() {
    document.querySelectorAll('.checkout-alert').forEach(el => el.remove());
}

// Update cart badge function (if not defined elsewhere)
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

// Add CSS animations
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
`;

if (!document.getElementById('checkout-animation-styles')) {
    style.id = 'checkout-animation-styles';
    document.head.appendChild(style);
}