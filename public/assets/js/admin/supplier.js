let deleteSupplierId = null;
let deleteModal = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ Supplier JS loaded!");

    const deleteModalEl = document.getElementById('delete-supplier-modal');
    if (deleteModalEl && typeof Modal !== 'undefined') {
        deleteModal = new Modal(deleteModalEl, {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-900 bg-opacity-30 backdrop-blur-sm fixed inset-0 z-40'
        });
    }

    const btnSaveAdd = document.getElementById('btn-save-add-supplier');
    if (btnSaveAdd) {
        btnSaveAdd.addEventListener('click', function(e) {
            e.stopPropagation();
            const form = document.getElementById('add-supplier-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'add');
            } else {
                form.reportValidity();
            }
        });
    }

    const btnSaveEdit = document.getElementById('btn-save-edit-supplier');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', function(e) {
            e.stopPropagation();
            const form = document.getElementById('edit-supplier-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'update');
            } else {
                form.reportValidity();
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            return;
        }

        const btnEdit = e.target.closest('.edit-supplier-btn');
        const btnDelete = e.target.closest('.delete-supplier-btn');

        if (btnEdit) {
            e.stopPropagation();
            document.getElementById('edit_supplier_id').value = btnEdit.dataset.id;
            document.getElementById('edit_supplier_name').value = btnEdit.dataset.name;
            document.getElementById('edit_supplier_contact').value = btnEdit.dataset.contact;
            document.getElementById('edit_supplier_address').value = btnEdit.dataset.address;
        }

        if (btnDelete) {
            e.stopPropagation();
            deleteSupplierId = btnDelete.dataset.id;
            if (deleteModal && !deleteModalEl.classList.contains('flex')) {
                deleteModal.show();
            }
        }
    });

    document.querySelectorAll('[data-modal-toggle="delete-supplier-modal"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (deleteModal) deleteModal.hide();
        });
    });

    const btnConfirmDelete = document.getElementById('btn-confirm-delete-supplier');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', async function(e) {
            e.stopPropagation();
            if (!deleteSupplierId) return;

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = 'Deleting...';

            try {
                const formData = new FormData();
                formData.append('id_supplier', deleteSupplierId);

                const response = await fetch('index.php?page=admin_supplier&action=delete', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Supplier deleted successfully!');
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
        const response = await fetch(`index.php?page=admin_supplier&action=${action}`, {
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