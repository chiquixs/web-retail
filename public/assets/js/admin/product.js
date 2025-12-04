// ==========================================
// PRODUCT MANAGER - FINAL FIXED VERSION
// ==========================================

let deleteProductId = null;
let searchTimeout = null;

// State awal
let currentFilters = {
    search: '',
    category: 'all',
    sortBy: 'id_product',
    sortOrder: 'DESC',
    page: 1
};

document.addEventListener('DOMContentLoaded', function() {
    console.log("✅ Product Manager Loaded - Fixed Version");

    // 1. Load Data Awal & Dropdown
    loadDropdowns();
    initializeFilters();
    loadPage(1);

    // 2. Event Listener: SEARCH
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            currentFilters.search = this.value;
            // Debounce: Tunggu 500ms setelah user berhenti mengetik
            searchTimeout = setTimeout(() => loadPage(1), 500);
        });
    }

    // 3. Event Listener: FILTER CATEGORY
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            currentFilters.category = this.value;
            loadPage(1);
        });
    }

    // 4. Event Listener: RESET BUTTON
    const resetBtn = document.getElementById('resetFilters');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Reset state
            currentFilters = {
                search: '',
                category: 'all',
                sortBy: 'id_product',
                sortOrder: 'DESC',
                page: 1
            };
            
            // Reset UI
            if (searchInput) searchInput.value = '';
            if (categoryFilter) categoryFilter.value = 'all';
            
            // Reset sorting arrows
            updateSortIndicators(currentFilters);
            
            // Reload data
            loadPage(1);
        });
    }

    // 5. Event Delegation: TOMBOL DI DALAM TABEL (Edit & Delete)
    const tableBody = document.getElementById('productTableBody');
    if (tableBody) {
        tableBody.addEventListener('click', function(e) {
            // Cek apakah yang diklik adalah tombol Edit (atau icon di dalamnya)
            const btnEdit = e.target.closest('.edit-product-btn');
            if (btnEdit) {
                e.preventDefault();
                handleEdit(btnEdit);
            }

            // Cek apakah yang diklik adalah tombol Delete
            const btnDelete = e.target.closest('.delete-product-btn');
            if (btnDelete) {
                e.preventDefault();
                deleteProductId = btnDelete.dataset.id;
                openModal('delete-product-modal');
            }
        });
    }

    // 6. Event Listener: CONFIRM DELETE
    const btnConfirmDelete = document.getElementById('btn-confirm-delete');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', handleDelete);
    }

    // 7. Event Listener: SAVE ADD & EDIT
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
// CORE LOGIC: SORTING & LOADING
// ==========================================

function sortTable(column) {
    console.log('📊 Sorting by:', column);
    
    // Toggle logic: Jika kolom sama, balik urutannya. Jika beda, set jadi ASC.
    if (currentFilters.sortBy === column) {
        currentFilters.sortOrder = currentFilters.sortOrder === 'ASC' ? 'DESC' : 'ASC';
    } else {
        currentFilters.sortBy = column;
        currentFilters.sortOrder = 'ASC';
    }
    
    updateSortIndicators(currentFilters);
    loadPage(1); // Kembali ke halaman 1 saat sorting berubah
}

function loadPage(page) {
    currentFilters.page = page;

    // Siapkan parameter URL
    const params = new URLSearchParams({
        page: 'admin_product',
        action: 'index',
        ajax: '1',
        p: page,
        search: currentFilters.search,
        category: currentFilters.category,
        sort_by: currentFilters.sortBy,
        sort_order: currentFilters.sortOrder
    });

    const url = `?${params.toString()}`;
    console.log('📡 Loading:', url);
    showLoading();

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            updateTable(data.products);
            updatePagination(data.pagination);
            updateShowingInfo(data.pagination);
        })
        .catch(err => {
            console.error('❌ Load error:', err);
            // alert('Failed to load data.');
        })
        .finally(() => {
            hideLoading();
        });
}

// ==========================================
// UI UPDATER FUNCTIONS
// ==========================================

function updateSortIndicators(filters) {
    // Reset semua panah jadi default (↕)
    document.querySelectorAll('[id^="sort-"]').forEach(el => {
        el.innerHTML = '↕'; // Atau gunakan SVG default
        el.classList.remove('text-blue-600', 'font-bold');
        el.classList.add('text-gray-400');
    });
    
    // Update panah kolom yang aktif
    const activeEl = document.getElementById(`sort-${filters.sortBy}`);
    if (activeEl) {
        activeEl.innerHTML = filters.sortOrder === 'ASC' ? '▲' : '▼';
        activeEl.classList.remove('text-gray-400');
        activeEl.classList.add('text-blue-600', 'font-bold');
    }
}

function updateTable(products) {
    const tbody = document.getElementById('productTableBody');
    if (!tbody) return;

    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="p-8 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="mt-2">No products found</p>
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    const formatter = new Intl.NumberFormat('id-ID');

    products.forEach(p => {
        // Safety check
        const price = p.price || 0;
        const stock = p.stock || 0;

        // Tentukan warna badge stock
        let stockClass = '';
        if (stock > 10) stockClass = 'bg-green-100 text-green-800';
        else if (stock > 0) stockClass = 'bg-yellow-100 text-yellow-800';
        else stockClass = 'bg-red-100 text-red-800';

        html += `
            <tr class="hover:bg-gray-50 transition border-b">
                <td class="p-3">
                    <img src="/web-retail-rev/public/assets/images/products/${escapeHtml(p.image)}" 
                         class="w-12 h-12 rounded object-cover border" 
                         onerror="this.src='https://via.placeholder.com/50?text=No+Img'">
                </td>
                <td class="p-3 font-medium text-gray-900">${escapeHtml(p.product_name || p.name)}</td>
                <td class="p-3 text-gray-600 text-sm">${escapeHtml(p.sku)}</td>
                <td class="p-3">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">
                        ${escapeHtml(p.category_name)}
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-600">${escapeHtml(p.supplier_name)}</td>
                <td class="p-3">
                    <span class="${stockClass} text-xs font-medium px-2 py-0.5 rounded">
                        ${stock}
                    </span>
                </td>
                <td class="p-3 text-sm font-semibold">Rp ${formatter.format(price)}</td>
                <td class="p-3">
                    <div class="flex gap-2">
                        <button class="edit-product-btn bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-1.5 rounded text-xs font-medium transition"
                            data-id="${p.id_product}"
                            data-name="${escapeHtml(p.product_name || p.name)}"
                            data-sku="${escapeHtml(p.sku)}"
                            data-price="${price}"
                            data-stock="${stock}"
                            data-category-id="${p.id_category}"
                            data-supplier-id="${p.id_supplier}">
                            Edit
                        </button>
                        <button class="delete-product-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition"
                            data-id="${p.id_product}">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

// ==========================================
// ACTION HANDLERS (EDIT, DELETE, FORM)
// ==========================================

function handleEdit(btn) {
    // 1. Populate Form
    document.getElementById('edit_id').value = btn.dataset.id;
    document.getElementById('edit_name').value = btn.dataset.name;
    document.getElementById('edit_sku').value = btn.dataset.sku;
    document.getElementById('edit_price').value = btn.dataset.price;
    document.getElementById('edit_stock').value = btn.dataset.stock;
    
    // Set Dropdowns (delay sedikit untuk memastikan options sudah ter-load)
    const catSelect = document.getElementById('edit_category');
    const supSelect = document.getElementById('edit_supplier');
    
    if(catSelect) catSelect.value = btn.dataset.categoryId;
    if(supSelect) supSelect.value = btn.dataset.supplierId;

    // Reset Image Input
    const imgInput = document.getElementById('edit_image');
    if (imgInput) imgInput.value = ''; 

    // 2. Buka Modal secara Manual
    openModal('edit-product-modal');
}

async function handleDelete() {
    if (!deleteProductId) return;

    const btn = document.getElementById('btn-confirm-delete');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Deleting...';

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
            loadPage(currentFilters.page); // Refresh tabel tanpa reload page
        } else {
            alert('❌ Failed: ' + data.message);
        }
    } catch (error) {
        console.error("Delete Error:", error);
        alert('❌ Server error.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

async function handleFormSubmit(form, action) {
    // Validasi HTML5 sederhana
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const btn = form.querySelector('button[type="button"]'); // Tombol save
    const originalText = btn ? btn.innerHTML : 'Save';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Processing...';
    }

    try {
        const formData = new FormData(form);
        const res = await fetch(`index.php?page=admin_product&action=${action}`, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            alert('✅ Success!');
            closeModal(action === 'add' ? 'add-product-modal' : 'edit-product-modal');
            form.reset(); // Bersihkan form
            loadPage(currentFilters.page); // Refresh data
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (e) {
        console.error(e);
        alert('❌ Server Error');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
}

// ==========================================
// HELPERS (MODAL, DROPDOWN, ETC)
// ==========================================

async function loadDropdowns() {
    try {
        // Ambil Categories & Suppliers secara paralel
        const [resCat, resSup] = await Promise.all([
            fetch('index.php?page=admin_product&action=get_categories').then(r => r.json()),
            fetch('index.php?page=admin_product&action=get_suppliers').then(r => r.json())
        ]);

        // Isi Dropdown Kategori (Add & Edit Form)
        let catOpts = '<option value="">Select Category</option>';
        if (resCat.success) {
            resCat.categories.forEach(c => catOpts += `<option value="${c.id_category}">${c.name}</option>`);
        }
        document.getElementById('add_category').innerHTML = catOpts;
        document.getElementById('edit_category').innerHTML = catOpts;

        // Isi Dropdown Filter (Atas Tabel)
        const filterEl = document.getElementById('categoryFilter');
        if (filterEl) {
            let filterOpts = '<option value="all">All Categories</option>';
            if (resCat.success) {
                resCat.categories.forEach(c => filterOpts += `<option value="${c.id_category}">${c.name}</option>`);
            }
            filterEl.innerHTML = filterOpts;
        }

        // Isi Dropdown Supplier (Add & Edit Form)
        let supOpts = '<option value="">Select Supplier</option>';
        if (resSup.success) {
            resSup.suppliers.forEach(s => supOpts += `<option value="${s.id_supplier}">${s.name}</option>`);
        }
        document.getElementById('add_supplier').innerHTML = supOpts;
        document.getElementById('edit_supplier').innerHTML = supOpts;

    } catch (err) {
        console.error('Load dropdowns error:', err);
    }
}

// Fungsi Manual Buka Modal (Pengganti data-modal-toggle)
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('role', 'dialog');

        // 🔥 Buat Backdrop Gelap & Blur jika belum ada
        if (!document.querySelector('[modal-backdrop]')) {
            const backdrop = document.createElement('div');
            backdrop.setAttribute('modal-backdrop', '');
            // perhatikan class 'backdrop-blur-sm' untuk efek blur
            backdrop.className = 'bg-gray-900 bg-opacity-50 fixed inset-0 z-40 backdrop-blur-sm transition-opacity';
            document.body.appendChild(backdrop);
            
            // Klik backdrop = tutup modal
            backdrop.addEventListener('click', () => closeModal(modalId));
        }
    }
}

// Fungsi Manual Tutup Modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.removeAttribute('aria-modal');
        modal.removeAttribute('role');
        
        // 🔥 Hapus Backdrop Blur
        const backdrop = document.querySelector('[modal-backdrop]');
        if (backdrop) {
            backdrop.remove();
        }
    }
}
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
    document.getElementById('tableContainer').classList.add('opacity-50');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
    document.getElementById('tableContainer').classList.remove('opacity-50');
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString().replace(/[&<>"']/g, m => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[m]));
}

function initializeFilters() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) currentFilters.search = searchInput.value;
}

function updatePagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (!container) return;
    
    if (pagination.total_pages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    // Logic Pagination Sederhana
    let html = `<nav class="flex justify-center items-center gap-2">`;
    
    // Prev
    html += `<button onclick="loadPage(${pagination.current_page - 1})" 
             ${!pagination.has_previous ? 'disabled' : ''} 
             class="px-3 py-1 border rounded bg-white hover:bg-gray-50 disabled:opacity-50">Prev</button>`;
             
    // Info Page
    html += `<span class="px-3 py-1 text-sm">Page ${pagination.current_page} of ${pagination.total_pages}</span>`;
    
    // Next
    html += `<button onclick="loadPage(${pagination.current_page + 1})" 
             ${!pagination.has_next ? 'disabled' : ''} 
             class="px-3 py-1 border rounded bg-white hover:bg-gray-50 disabled:opacity-50">Next</button>`;
             
    html += `</nav>`;
    
    container.innerHTML = html;
}

function updateShowingInfo(pagination) {
    const infoEl = document.getElementById('showingInfo');
    if (infoEl) {
        infoEl.textContent = `Showing ${pagination.from} to ${pagination.to} of ${pagination.total_records} entries`;
    }
}