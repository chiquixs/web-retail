let deleteCustomerId = null;
let deleteModal = null;

document.addEventListener('DOMContentLoaded', function () {
    console.log("✅ Customer JS loaded!");

    // Delete modal
    const deleteModalEl = document.getElementById('delete-customer-modal');
    if (deleteModalEl && typeof Modal !== 'undefined') {
        deleteModal = new Modal(deleteModalEl, {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-900 bg-opacity-30 backdrop-blur-sm fixed inset-0 z-40'
        });
    }

    // Add customer
    const btnSaveAdd = document.getElementById('btn-save-add-customer');
    if (btnSaveAdd) {
        btnSaveAdd.addEventListener('click', function (e) {
            e.stopPropagation();
            const form = document.getElementById('add-customer-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'add');
            } else {
                form.reportValidity();
            }
        });
    }

    // Edit customer
    const btnSaveEdit = document.getElementById('btn-save-edit-customer');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', function (e) {
            e.stopPropagation();
            const form = document.getElementById('edit-customer-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'update');
            } else {
                form.reportValidity();
            }
        });
    }

    // Handle edit & delete button clicks
    document.addEventListener('click', function (e) {

        // Edit button
        const btnEdit = e.target.closest('.edit-customer-btn');
        if (btnEdit) {
            e.stopPropagation();

            document.getElementById('edit_customer_id').value = btnEdit.dataset.id;
            document.getElementById('edit_customer_name').value = btnEdit.dataset.name;
            document.getElementById('edit_customer_email').value = btnEdit.dataset.email;
            document.getElementById('edit_customer_address').value = btnEdit.dataset.address;
        }

        // Delete button
        const btnDelete = e.target.closest('.delete-customer-btn');
        if (btnDelete) {
            e.stopPropagation();

            deleteCustomerId = btnDelete.dataset.id;
            console.log("Delete customer ID:", deleteCustomerId);

            if (deleteModal && !deleteModalEl.classList.contains('flex')) {
                deleteModal.show();
            }
        }
    });

    // Close delete modal
    document.querySelectorAll('[data-modal-toggle="delete-customer-modal"]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (deleteModal) deleteModal.hide();
        });
    });

    // Confirm delete
    const btnConfirmDelete = document.getElementById('btn-confirm-delete-customer');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', async function (e) {
            e.stopPropagation();

            if (!deleteCustomerId) return;

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = 'Deleting...';

            try {
                const formData = new FormData();
                formData.append('id_customer', deleteCustomerId);

                const response = await fetch('index.php?page=admin_customer&action=delete', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Customer deleted!');
                    location.reload();
                } else {
                    alert('❌ Failed: ' + data.message);
                    deleteModal.hide();
                }

            } catch (error) {
                console.error("Delete Error:", error);
                alert('❌ Server error.');
                deleteModal.hide();
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    }
});

// Submit form (Add / Edit)
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
        const response = await fetch(`index.php?page=admin_customer&action=${action}`, {
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