let deleteCategoryId = null;
let deleteModal = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ Category JS loaded!");

    // Initialize delete modal
    const deleteModalEl = document.getElementById('delete-category-modal');
    if (deleteModalEl && typeof Modal !== 'undefined') {
        deleteModal = new Modal(deleteModalEl, {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-900 bg-opacity-30 backdrop-blur-sm fixed inset-0 z-40'
        });
    }

    // Add category
    const btnSaveAdd = document.getElementById('btn-save-add-category');
    if (btnSaveAdd) {
        btnSaveAdd.addEventListener('click', function(e) {
            e.stopPropagation();
            const form = document.getElementById('add-category-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'add');
            } else {
                form.reportValidity();
            }
        });
    }

    // Edit category
    const btnSaveEdit = document.getElementById('btn-save-edit-category');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', function(e) {
            e.stopPropagation();
            const form = document.getElementById('edit-category-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'update');
            } else {
                form.reportValidity();
            }
        });
    }

    // Edit and Delete button clicks
    document.addEventListener('click', function(e) {
        // Prevent backdrop clicks
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            return;
        }

        const btnEdit = e.target.closest('.edit-category-btn');
        const btnDelete = e.target.closest('.delete-category-btn');

        if (btnEdit) {
            e.stopPropagation();
            document.getElementById('edit_category_id').value = btnEdit.dataset.id;
            document.getElementById('edit_category_name').value = btnEdit.dataset.name;
            document.getElementById('edit_category_description').value = btnEdit.dataset.description;
        }

        if (btnDelete) {
            e.stopPropagation();
            deleteCategoryId = btnDelete.dataset.id;
            console.log("Delete category ID:", deleteCategoryId);
            
            if (deleteModal && !deleteModalEl.classList.contains('flex')) {
                deleteModal.show();
            }
        }
    });

    // Close modal buttons
    document.querySelectorAll('[data-modal-toggle="delete-category-modal"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (deleteModal) {
                deleteModal.hide();
            }
        });
    });

    // Delete confirmation
    const btnConfirmDelete = document.getElementById('btn-confirm-delete-category');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', async function(e) {
            e.stopPropagation();
            
            if (!deleteCategoryId) return;

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
                    location.reload();
                } else {
                    alert('❌ Failed: ' + data.message);
                    if (deleteModal) deleteModal.hide();
                }
            } catch (error) {
                console.error("Delete Error:", error);
                alert('❌ Server error.');
                if (deleteModal) deleteModal.hide();
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    }
});

// Submit form
async function handleFormSubmit(form, action) {
    const formData = new FormData(form);
    const modal = form.closest('.relative');
    const btn = modal ? modal.querySelector('button[id^="btn-save"]') : null;
    
    if (btn) {
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Processing...';
    }

    try {
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