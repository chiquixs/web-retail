// ==========================================
// CATEGORY MANAGER - FIXED MODAL VERSION
// ==========================================

let deleteCategoryId = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ Category JS Loaded - Fixed Modal Version");

    // 1. Event Delegation untuk semua tombol
    document.body.addEventListener('click', function(e) {
        
        // ========================================
        // HANDLE OPEN MODAL (Add Category)
        // ========================================
        const btnOpenAdd = e.target.closest('[data-modal-target="add-category-modal"]');
        if (btnOpenAdd && !btnOpenAdd.hasAttribute('data-modal-toggle')) {
            e.preventDefault();
            openModal('add-category-modal');
            return;
        }

        // ========================================
        // HANDLE TOMBOL EDIT
        // ========================================
        const btnEdit = e.target.closest('.edit-category-btn');
        if (btnEdit) {
            e.preventDefault();
            e.stopPropagation();
            console.log("✏️ Edit clicked:", btnEdit.dataset);
            
            // Isi form
            document.getElementById('edit_category_id').value = btnEdit.dataset.id;
            document.getElementById('edit_category_name').value = btnEdit.dataset.name;
            document.getElementById('edit_category_description').value = btnEdit.dataset.description;
            
            openModal('edit-category-modal');
            return;
        }

        // ========================================
        // HANDLE TOMBOL DELETE
        // ========================================
        const btnDelete = e.target.closest('.delete-category-btn');
        if (btnDelete) {
            e.preventDefault();
            e.stopPropagation();
            deleteCategoryId = btnDelete.dataset.id;
            console.log("🗑️ Delete Request for ID:", deleteCategoryId);
            openModal('delete-category-modal');
            return;
        }

        // ========================================
        // HANDLE CLOSE MODAL (X button & Cancel)
        // ========================================
        const btnClose = e.target.closest('[data-modal-toggle]');
        if (btnClose) {
            e.preventDefault();
            e.stopPropagation();
            const modalId = btnClose.getAttribute('data-modal-toggle');
            console.log("❌ Closing modal:", modalId);
            closeModal(modalId);
            return;
        }
    });

    // ========================================
    // CONFIRM DELETE
    // ========================================
    const btnConfirmDelete = document.getElementById('btn-confirm-delete-category');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', async function() {
            if (!deleteCategoryId) {
                console.error("❌ No Category ID selected");
                return;
            }

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = 'Deleting...';

            try {
                const formData = new FormData();
                formData.append('id_category', deleteCategoryId);

                const response = await fetch('index.php?page=admin_category&action=delete', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Category deleted successfully!');
                    closeModal('delete-category-modal');
                    location.reload();
                } else {
                    alert('❌ Failed: ' + data.message);
                }
            } catch (error) {
                console.error("Delete Error:", error);
                alert('❌ Server error.');
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    }

    // ========================================
    // SAVE ADD
    // ========================================
    const btnSaveAdd = document.getElementById('btn-save-add-category');
    if (btnSaveAdd) {
        btnSaveAdd.addEventListener('click', () => {
            const form = document.getElementById('add-category-form');
            if (form.checkValidity()) handleFormSubmit(form, 'add');
            else form.reportValidity();
        });
    }

    // ========================================
    // SAVE EDIT
    // ========================================
    const btnSaveEdit = document.getElementById('btn-save-edit-category');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', () => {
            const form = document.getElementById('edit-category-form');
            if (form.checkValidity()) handleFormSubmit(form, 'update');
            else form.reportValidity();
        });
    }
});

// ==========================================
// HELPER FUNCTIONS
// ==========================================

async function handleFormSubmit(form, action) {
    const btn = form.closest('.relative').querySelector('button[id^="btn-save"]');
    const originalText = btn ? btn.innerHTML : 'Save';
    
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Processing...';
    }

    try {
        const formData = new FormData(form);
        const response = await fetch(`index.php?page=admin_category&action=${action}`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ Success!');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Server error.');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
}

// ========================================
// OPEN MODAL - Remove all existing backdrops first
// ========================================
function openModal(modalId) {
    console.log("🔓 Opening modal:", modalId);
    
    // Remove any existing backdrop FIRST
    const existingBackdrop = document.querySelector('[modal-backdrop]');
    if (existingBackdrop) {
        existingBackdrop.remove();
        console.log("🧹 Removed existing backdrop");
    }
    
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('role', 'dialog');

        // Create new backdrop
        const backdrop = document.createElement('div');
        backdrop.setAttribute('modal-backdrop', '');
        backdrop.className = 'bg-gray-900 bg-opacity-50 fixed inset-0 z-40';
        document.body.appendChild(backdrop);
        
        // Click backdrop to close
        backdrop.addEventListener('click', () => {
            console.log("🖱️ Backdrop clicked");
            closeModal(modalId);
        });
        
        console.log("✅ Modal opened:", modalId);
    }
}

// ========================================
// CLOSE MODAL - Clean up everything
// ========================================
function closeModal(modalId) {
    console.log("🔒 Closing modal:", modalId);
    
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.removeAttribute('aria-modal');
        modal.removeAttribute('role');
    }
    
    // Remove ALL backdrops
    const allBackdrops = document.querySelectorAll('[modal-backdrop]');
    allBackdrops.forEach(backdrop => {
        backdrop.remove();
        console.log("🧹 Backdrop removed");
    });
    
    console.log("✅ Modal closed:", modalId);
}