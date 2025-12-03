// ==========================================
// 1. GLOBAL VARIABLES
// ==========================================
let deleteProductId = null;
let searchTimeout = null;
const searchInput = document.getElementById('searchInput');

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ Product Manager Loaded (Fix Modal)");

    loadDropdowns();

    // Search Logic
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadPage(1, this.value);
            }, 500);
        });
    }

    // ==========================================
    // 2. EVENT DELEGATION (MAIN LOGIC)
    // ==========================================
    const tableBody = document.getElementById('productTableBody');
    
    if (tableBody) {
        tableBody.addEventListener('click', function(e) {
            // Kita cari elemen terdekat yang punya class tombol
            const btnEdit = e.target.closest('.edit-product-btn');
            const btnDelete = e.target.closest('.delete-product-btn');

            // --- TOMBOL EDIT ---
            if (btnEdit) {
                e.preventDefault();
                document.getElementById('edit_id').value = btnEdit.dataset.id;
                document.getElementById('edit_name').value = btnEdit.dataset.name;
                document.getElementById('edit_sku').value = btnEdit.dataset.sku;
                document.getElementById('edit_price').value = btnEdit.dataset.price;
                document.getElementById('edit_stock').value = btnEdit.dataset.stock;
                document.getElementById('edit_category').value = btnEdit.dataset.categoryId;
                document.getElementById('edit_supplier').value = btnEdit.dataset.supplierId;
                
                // Reset input file
                const imgInput = document.getElementById('edit_image');
                if(imgInput) imgInput.value = ''; 

                // Note: Tombol Edit kita biarkan pakai data-modal-toggle bawaan HTML
                // karena tidak perlu simpan ID global seperti delete.
            }

            // --- TOMBOL DELETE ---
            if (btnDelete) {
                e.preventDefault();
                e.stopPropagation(); // Stop event lain
                
                deleteProductId = btnDelete.dataset.id;
                console.log("Delete ID:", deleteProductId);
                
                // BUKA MODAL SECARA MANUAL
                openModal('delete-product-modal');
            }
        });
    }

    // ==========================================
    // 3. MODAL ACTIONS
    // ==========================================
    
    // Konfirmasi Delete (YES)
    const btnConfirmDelete = document.getElementById('btn-confirm-delete');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', async function() {
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
                    closeModal('delete-product-modal');
                    loadPage(getCurrentPage()); 
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

    // Tombol Close (Silang & Cancel & Backdrop)
    // Menutup modal apapun yang terbuka
    document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Cek apakah tombol ini untuk menutup delete modal
            if(this.getAttribute('data-modal-toggle') === 'delete-product-modal') {
                e.preventDefault();
                e.stopPropagation();
                closeModal('delete-product-modal');
            }
        });
    });

    // Form Submit Handlers
    const btnSaveAdd = document.getElementById('btn-save-add');
    if (btnSaveAdd) {
        btnSaveAdd.addEventListener('click', () => handleFormSubmit(document.getElementById('add-product-form'), 'add'));
    }

    const btnSaveEdit = document.getElementById('btn-save-edit');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', () => handleFormSubmit(document.getElementById('edit-product-form'), 'update'));
    }
});

// ==========================================
// 4. MANUAL MODAL CONTROL (INTI SOLUSI)
// ==========================================

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if(modal) {
        // 1. Munculkan Modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('role', 'dialog');

        // 2. Munculkan Backdrop (Layar Gelap) Manual
        // Cek dulu biar gak dobel
        if(!document.querySelector('[modal-backdrop]')) {
            const backdrop = document.createElement('div');
            backdrop.setAttribute('modal-backdrop', '');
            backdrop.className = 'bg-gray-900 bg-opacity-50 fixed inset-0 z-40';
            document.body.appendChild(backdrop);
            
            // Tutup modal kalau backdrop diklik
            backdrop.addEventListener('click', () => closeModal(modalId));
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if(modal) {
        // 1. Sembunyikan Modal
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.removeAttribute('aria-modal');
        modal.removeAttribute('role');
        
        // 2. Hapus Backdrop
        const backdrop = document.querySelector('[modal-backdrop]');
        if (backdrop) {
            backdrop.remove();
        }
    }
}

// ==========================================
// 5. AJAX & TABLE RENDERING
// ==========================================

function loadPage(page, search = null) {
    const searchValue = search !== null ? search : (searchInput ? searchInput.value : '');
    const url = `?page=admin_product&action=index&ajax=1&p=${page}&search=${encodeURIComponent(searchValue)}`;
    
    showLoading();
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            updateTable(data.products);
            updatePagination(data.pagination);
            updateShowingInfo(data.pagination);
        })
        .catch(err => console.error(err))
        .finally(() => hideLoading());
}

function updateTable(products) {
    const tbody = document.getElementById('productTableBody');
    if (!tbody) return;
    
    if (products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="p-8 text-center text-gray-500">No products found</td></tr>`;
        return;
    }
    
    let html = '';
    products.forEach(p => {
        
        let stockClass = '';

        // --- LOGIKA HYBRID (PENTING!) ---
        // 1. Cek apakah ada data 'status_stok' dari database/Function SQL?
        if (p.status_stok) {
            // Kalau ada, pakai logika Database
            if (p.status_stok === 'instok') {
                stockClass = 'bg-green-100 text-green-800';
            } else if (p.status_stok === 'low') {
                stockClass = 'bg-yellow-100 text-yellow-800';
            } else {
                stockClass = 'bg-red-100 text-red-800';
            }
        } 

        html += `
            <tr class="hover:bg-gray-50 transition">
                <td class="p-3"><img src="/web-retail-rev/public/assets/images/products/${escapeHtml(p.image)}" class="w-16 h-16 rounded object-cover" onerror="this.onerror=null;this.src='assets/images/placeholder.png';"></td>
                <td class="p-3"><div class="text-sm font-medium text-gray-900">${escapeHtml(p.product_name)}</div></td>
                <td class="p-3"><span class="text-sm text-gray-600">${escapeHtml(p.sku)}</span></td>
                <td class="p-3"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${escapeHtml(p.category_name)}</span></td>
                <td class="p-3"><span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">${escapeHtml(p.supplier_name)}</span></td>
                
                <td class="p-3"><span class="px-2 py-1 text-xs font-semibold rounded ${stockClass}">${p.stock}</span></td>
                
                <td class="p-3"><span class="text-sm font-semibold text-gray-900">Rp ${formatNumber(p.price)}</span></td>
                <td class="p-3">
                    <div class="flex items-center space-x-2">
                        <button class="edit-product-btn bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-1.5 rounded text-xs font-medium transition"
                            data-modal-target="edit-product-modal"
                            data-modal-toggle="edit-product-modal"
                            data-id="${p.id_product}"
                            data-name="${escapeHtml(p.product_name)}"
                            data-sku="${escapeHtml(p.sku)}"
                            data-price="${p.price}"
                            data-stock="${p.stock}"
                            data-category-id="${p.id_category}"
                            data-supplier-id="${p.id_supplier}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button class="delete-product-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition"
                            data-id="${p.id_product}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}
// ... (Helper Functions lainnya: loadDropdowns, updatePagination, showLoading, dll tetap sama)
function getCurrentPage() {
    const activeBtn = document.querySelector('#paginationContainer .bg-cyan-600');
    return activeBtn ? parseInt(activeBtn.innerText) : 1;
}

function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    const tableContainer = document.getElementById('tableContainer');
    if (overlay) overlay.classList.remove('hidden');
    if (tableContainer) tableContainer.classList.add('opacity-50');
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    const tableContainer = document.getElementById('tableContainer');
    if (overlay) overlay.classList.add('hidden');
    if (tableContainer) tableContainer.classList.remove('opacity-50');
}

function updateShowingInfo(pagination) {
    const infoEl = document.getElementById('showingInfo');
    if (infoEl) infoEl.textContent = `Showing ${pagination.from} to ${pagination.to} of ${pagination.total_records} entries`;
}

function escapeHtml(text) {
    return text ? text.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])) : '';
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (!container) return;
    if (pagination.total_pages <= 1) { container.innerHTML = ''; return; }
    
    const start = Math.max(1, pagination.current_page - 2);
    const end = Math.min(pagination.total_pages, pagination.current_page + 2);
    let pageNumbers = '';
    for (let i = start; i <= end; i++) {
        const activeClass = i === pagination.current_page ? 'bg-cyan-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
        pageNumbers += `<button onclick="loadPage(${i})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium ${activeClass}">${i}</button>`;
    }
    container.innerHTML = `<nav class="flex items-center justify-between"><div class="flex-1 flex justify-center mt-4"><button onclick="loadPage(${pagination.current_page - 1})" ${!pagination.has_previous ? 'disabled' : ''} class="px-3 py-1 border rounded mr-2 disabled:opacity-50">Prev</button>${pageNumbers}<button onclick="loadPage(${pagination.current_page + 1})" ${!pagination.has_next ? 'disabled' : ''} class="px-3 py-1 border rounded ml-2 disabled:opacity-50">Next</button></div></nav>`;
}

async function loadDropdowns() {
    try {
        const resCat = await fetch('index.php?page=admin_product&action=get_categories');
        const dataCat = await resCat.json();
        let catOpts = '<option value="">Select Category</option>';
        if (dataCat.success) { dataCat.categories.forEach(c => catOpts += `<option value="${c.id_category}">${c.name}</option>`); }
        document.getElementById('add_category').innerHTML = catOpts;
        document.getElementById('edit_category').innerHTML = catOpts;

        const resSup = await fetch('index.php?page=admin_product&action=get_suppliers');
        const dataSup = await resSup.json();
        let supOpts = '<option value="">Select Supplier</option>';
        if (dataSup.success) { dataSup.suppliers.forEach(s => supOpts += `<option value="${s.id_supplier}">${s.name}</option>`); }
        document.getElementById('add_supplier').innerHTML = supOpts;
        document.getElementById('edit_supplier').innerHTML = supOpts;
    } catch (err) { console.error(err); }
}

async function handleFormSubmit(form, action) {
    if (!form.checkValidity()) { form.reportValidity(); return; }
    const btn = form.querySelector('button[type="button"]');
    const originalText = btn ? btn.innerHTML : 'Save';
    if(btn) { btn.disabled = true; btn.innerHTML = 'Processing...'; }

    try {
        const formData = new FormData(form);
        const res = await fetch(`index.php?page=admin_product&action=${action}`, { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) { alert('✅ Success!'); location.reload(); } else { alert('❌ Error: ' + data.message); }
    } catch (e) { console.error(e); alert('❌ Server Error'); } finally { if(btn) { btn.disabled = false; btn.innerHTML = originalText; } }
}