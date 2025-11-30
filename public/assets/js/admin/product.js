
//     // Variabel Global
//     let deleteProductId = null;
//     let deleteModal = null; // Kita siapkan variabel untuk objek Modal Flowbite

//     document.addEventListener('DOMContentLoaded', function() {
//         console.log("✅ Javascript Siap!");
//         loadDropdowns();

//         // === INISIALISASI MODAL DELETE (Supaya Backdrop Muncul) ===
//         const deleteModalEl = document.getElementById('delete-product-modal');
//         if (deleteModalEl) {
//             // Kita pakai fitur resmi Flowbite
//             // Pastikan script flowbite.js sudah diload di head/body
//             deleteModal = new Modal(deleteModalEl, {
//                 placement: 'center',
//                 backdrop: 'dynamic',
//                 backdropClasses: 'bg-gray-900 bg-opacity-50 fixed inset-0 z-40',
//                 closable: true
//             });
//         }

//         // === LOGIKA TOMBOL CANCEL / SILANG DI MODAL DELETE ===
//         // Kita handle manual tutupnya biar rapi
//         const closeDeleteBtns = document.querySelectorAll('[data-modal-toggle="delete-product-modal"]');
//         closeDeleteBtns.forEach(btn => {
//             btn.addEventListener('click', function() {
//                 if (deleteModal) deleteModal.hide();
//             });
//         });


//         // === BAGIAN ADD PRODUCT ===
//         const openAddModalBtn = document.querySelector('[data-modal-toggle="add-product-modal"]');
//         if (openAddModalBtn) {
//             openAddModalBtn.addEventListener('click', function() {
//                 document.getElementById('add-product-form').reset();
//                 document.getElementById('image-preview').classList.add('hidden');
//                 document.getElementById('preview-img').src = "";
//             });
//         }

//         const imageInput = document.getElementById('image');
//         if (imageInput) {
//             imageInput.addEventListener('change', function(e) {
//                 const file = e.target.files[0];
//                 if (file) {
//                     const reader = new FileReader();
//                     reader.onload = function(e) {
//                         document.getElementById('preview-img').src = e.target.result;
//                         document.getElementById('image-preview').classList.remove('hidden');
//                     };
//                     reader.readAsDataURL(file);
//                 }
//             });
//         }

//         const btnSaveAdd = document.getElementById('btn-save-add');
//         if (btnSaveAdd) {
//             btnSaveAdd.addEventListener('click', function() {
//                 const form = document.getElementById('add-product-form');
//                 if (form && form.checkValidity()) {
//                     handleFormSubmit(form, 'add_product.php');
//                 } else {
//                     form.reportValidity();
//                 }
//             });
//         }

//         // === BAGIAN EDIT & DELETE PRODUCT ===
//         document.addEventListener('click', function(e) {
//             const btnEdit = e.target.closest('.edit-product-btn');
//             const btnDelete = e.target.closest('.delete-product-btn');

//             // --- TOMBOL EDIT ---
//             if (btnEdit) {
//                 document.getElementById('edit_id').value = btnEdit.dataset.id;
//                 document.getElementById('edit_name').value = btnEdit.dataset.name;
//                 document.getElementById('edit_sku').value = btnEdit.dataset.sku;
//                 document.getElementById('edit_price').value = btnEdit.dataset.price;
//                 document.getElementById('edit_stock').value = btnEdit.dataset.stock;
//                 document.getElementById('edit_category').value = btnEdit.dataset.categoryId;
//                 document.getElementById('edit_supplier').value = btnEdit.dataset.supplierId;
//             }

//             // --- TOMBOL DELETE (PERBAIKAN DI SINI) ---
//             if (btnDelete) {
//                 deleteProductId = btnDelete.dataset.id;
//                 console.log("Mau hapus ID:", deleteProductId);

//                 // GUNAKAN FUNGSI RESMI FLOWBITE: show()
//                 // Ini yang bikin layar jadi abu-abu otomatis
//                 if (deleteModal) {
//                     deleteModal.show();
//                 } else {
//                     // Fallback kalau Flowbite gagal load
//                     const m = document.getElementById('delete-product-modal');
//                     m.classList.remove('hidden');
//                     m.classList.add('flex');
//                 }
//             }
//         });

//         // Tombol Save Edit
//         const btnSaveEdit = document.getElementById('btn-save-edit');
//         if (btnSaveEdit) {
//             btnSaveEdit.addEventListener('click', function() {
//                 const form = document.getElementById('edit-product-form');
//                 if (form && form.checkValidity()) {
//                     handleFormSubmit(form, 'edit_product.php');
//                 } else {
//                     form.reportValidity();
//                 }
//             });
//         }

//         // === TOMBOL KONFIRMASI DELETE (YES) ===
//         const btnConfirmDelete = document.getElementById('btn-confirm-delete');
//         if (btnConfirmDelete) {
//             btnConfirmDelete.addEventListener('click', async function() {
//                 if (!deleteProductId) return;

//                 const originalText = this.innerHTML;
//                 this.disabled = true;
//                 this.innerHTML = 'Deleting...';

//                 try {
//                     const formData = new FormData();
//                     formData.append('id_product', deleteProductId);

//                     const response = await fetch('delete_product.php', {
//                         method: 'POST',
//                         body: formData
//                     });

//                     const text = await response.text();
//                     const data = JSON.parse(text);

//                     if (data.success) {
//                         alert('✅ Produk berhasil dihapus!');
//                         location.reload();
//                     } else {
//                         alert('❌ Gagal hapus: ' + data.message);
//                         if (deleteModal) deleteModal.hide(); // Tutup modal kalau gagal
//                     }
//                 } catch (error) {
//                     console.error("Delete Error:", error);
//                     alert('❌ Terjadi kesalahan server.');
//                     if (deleteModal) deleteModal.hide();
//                 } finally {
//                     this.disabled = false;
//                     this.innerHTML = originalText;
//                 }
//             });
//         }
//     });

//     // === FUNGSI UMUM KIRIM DATA ===
//   // Load dropdown categories
// async function loadDropdowns() {
//     try {
//         // Get categories - PERBAIKI URL INI
//         const resCat = await fetch('index.php?page=admin_product&action=get_categories');
//         const dataCat = await resCat.json();
//         let catOpts = '<option value="">Select Category</option>';
//         if (dataCat.success) {
//             dataCat.categories.forEach(c => {
//                 catOpts += `<option value="${c.id_category}">${c.name}</option>`;
//             });
//         }
//         document.getElementById('add_category').innerHTML = catOpts;
//         document.getElementById('edit_category').innerHTML = catOpts;

//         // Get suppliers - PERBAIKI URL INI
//         const resSup = await fetch('index.php?page=admin_product&action=get_suppliers');
//         const dataSup = await resSup.json();
//         let supOpts = '<option value="">Select Supplier</option>';
//         if (dataSup.success) {
//             dataSup.suppliers.forEach(s => {
//                 supOpts += `<option value="${s.id_supplier}">${s.name}</option>`;
//             });
//         }
//         document.getElementById('add_supplier').innerHTML = supOpts;
//         document.getElementById('edit_supplier').innerHTML = supOpts;
//     } catch (err) {
//         console.error('Failed to load dropdowns:', err);
//     }
// }

// // Submit form - PERBAIKI URL INI
// async function handleFormSubmit(form, action) {
//     const formData = new FormData(form);
    
//     const response = await fetch(`index.php?page=admin_product&action=${action}`, {
//         method: 'POST',
//         body: formData
//     });
    
//     const data = await response.json();
    
//     if (data.success) {
//         alert('Success!');
//         location.reload();
//     } else {
//         alert('Error: ' + data.message);
//     }
// }

// const cmsProductController = {
//     delete: function(id) {
//         deleteProductId = id;

//         if (!deleteModal) {
//             const modalEl = document.getElementById('delete-product-modal');
//             deleteModal = new Modal(modalEl);
//         }

//         deleteModal.show();
//     }
// };

let deleteProductId = null;
let deleteModal = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ Product JS loaded!");
    loadDropdowns();

    // ✅ Initialize delete modal
    const deleteModalEl = document.getElementById('delete-product-modal');
    if (deleteModalEl && typeof Modal !== 'undefined') {
        deleteModal = new Modal(deleteModalEl, {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-900 bg-opacity-30 backdrop-blur-sm fixed inset-0 z-40'
        });
    }

    // Add product
    const btnSaveAdd = document.getElementById('btn-save-add');
    if (btnSaveAdd) {
        btnSaveAdd.addEventListener('click', function() {
            const form = document.getElementById('add-product-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'add');
            } else {
                form.reportValidity();
            }
        });
    }

    // Edit product
    const btnSaveEdit = document.getElementById('btn-save-edit');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', function() {
            const form = document.getElementById('edit-product-form');
            if (form && form.checkValidity()) {
                handleFormSubmit(form, 'update');
            } else {
                form.reportValidity();
            }
        });
    }

    // Image preview
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ✅ PERBAIKAN: Event listener untuk Edit dan Delete
    document.addEventListener('click', function(e) {
        // ✅ Cegah event bubbling dari backdrop
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            return; // Jangan proses klik di backdrop
        }

        const btnEdit = e.target.closest('.edit-product-btn');
        const btnDelete = e.target.closest('.delete-product-btn');

        if (btnEdit) {
            // ✅ Cegah event propagation
            e.stopPropagation();
            
            document.getElementById('edit_id').value = btnEdit.dataset.id;
            document.getElementById('edit_name').value = btnEdit.dataset.name;
            document.getElementById('edit_sku').value = btnEdit.dataset.sku;
            document.getElementById('edit_price').value = btnEdit.dataset.price;
            document.getElementById('edit_stock').value = btnEdit.dataset.stock;
            document.getElementById('edit_category').value = btnEdit.dataset.categoryId;
            document.getElementById('edit_supplier').value = btnEdit.dataset.supplierId;
        }

        if (btnDelete) {
            // ✅ Cegah event propagation
            e.stopPropagation();
            
            deleteProductId = btnDelete.dataset.id;
            console.log("Delete product ID:", deleteProductId);
            
            // ✅ Pastikan modal belum terbuka sebelum buka lagi
            if (deleteModal && !deleteModalEl.classList.contains('flex')) {
                deleteModal.show();
            }
        }
    });

    // ✅ Close modal buttons
    document.querySelectorAll('[data-modal-toggle="delete-product-modal"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (deleteModal) {
                deleteModal.hide();
            }
        });
    });

    // Delete confirmation
    const btnConfirmDelete = document.getElementById('btn-confirm-delete');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', async function(e) {
            e.stopPropagation(); // ✅ Prevent bubbling
            
            if (!deleteProductId) return;

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = 'Deleting...';

            try {
                const formData = new FormData();
                formData.append('id_product', deleteProductId);

                const response = await fetch('index.php?page=admin_product&action=delete', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Product deleted successfully!');
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

// Load dropdowns
async function loadDropdowns() {
    try {
        const resCat = await fetch('index.php?page=admin_product&action=get_categories');
        const dataCat = await resCat.json();
        let catOpts = '<option value="">Select Category</option>';
        if (dataCat.success) {
            dataCat.categories.forEach(c => {
                catOpts += `<option value="${c.id_category}">${c.name}</option>`;
            });
        }
        document.getElementById('add_category').innerHTML = catOpts;
        document.getElementById('edit_category').innerHTML = catOpts;

        const resSup = await fetch('index.php?page=admin_product&action=get_suppliers');
        const dataSup = await resSup.json();
        let supOpts = '<option value="">Select Supplier</option>';
        if (dataSup.success) {
            dataSup.suppliers.forEach(s => {
                supOpts += `<option value="${s.id_supplier}">${s.name}</option>`;
            });
        }
        document.getElementById('add_supplier').innerHTML = supOpts;
        document.getElementById('edit_supplier').innerHTML = supOpts;
    } catch (err) {
        console.error('Failed to load dropdowns:', err);
    }
}

// Submit form
async function handleFormSubmit(form, action) {
    const formData = new FormData(form);
    const btn = form.closest('.relative').querySelector('button[type="button"]');
    
    if (btn) {
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Processing...';
    }

    try {
        const response = await fetch(`index.php?page=admin_product&action=${action}`, {
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