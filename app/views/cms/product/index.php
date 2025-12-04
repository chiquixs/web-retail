<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="pt-6 px-4 w-full">
    <div class="bg-white shadow rounded-lg p-6">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Product Management</h3>
            <button type="button" onclick="openModal('add-product-modal')"
                class="text-white bg-pink-600 hover:bg-pink-700 focus:ring-4 focus:ring-blue-200 font-medium rounded-lg text-sm px-4 py-2 inline-flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </button>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4">
            <div class="flex-1 w-full md:w-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                        placeholder="Search product, SKU, or supplier..."
                        value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                </div>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <select id="categoryFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    <option value="all">All Categories</option>
                    </select>

                <button id="resetFilters" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Reset
                </button>
            </div>
        </div>

        <div id="loadingOverlay" class="hidden">
            <div class="flex items-center justify-center py-8">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-3 text-gray-600">Loading data...</span>
            </div>
        </div>

        <div class="w-full overflow-x-auto" id="tableContainer">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                        
                        <th onclick="sortTable('product_name')" class="p-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none group">
                            Name <span id="sort-product_name" class="text-gray-400">↕</span>
                        </th>
                        <th onclick="sortTable('sku')" class="p-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none group">
                            SKU <span id="sort-sku" class="text-gray-400">↕</span>
                        </th>
                        <th onclick="sortTable('category_name')" class="p-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none group">
                            Category <span id="sort-category_name" class="text-gray-400">↕</span>
                        </th>
                        
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        
                        <th onclick="sortTable('stock')" class="p-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none group">
                            Stock <span id="sort-stock" class="text-gray-400">↕</span>
                        </th>
                        <th onclick="sortTable('price')" class="p-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none group">
                            Price <span id="sort-price" class="text-gray-400">↕</span>
                        </th>
                        
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="bg-white divide-y divide-gray-200">
                    <?php if (empty($data['products'])): ?>
                        <tr><td colspan="8" class="p-4 text-center text-gray-500">No products found.</td></tr>
                    <?php else: ?>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4" id="paginationContainer"></div>
    </div>
</div>

<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="add-product-modal">
    <div class="relative w-full max-w-2xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
             <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Add Product</h3>
                <button type="button" onclick="closeModal('add-product-modal')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">X</button>
            </div>
            <div class="p-6">
                <form id="add-product-form" enctype="multipart/form-data">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                            <input type="text" name="name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">SKU</label>
                            <input type="text" name="sku" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                            <select name="id_category" id="add_category" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required></select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Supplier</label>
                            <select name="id_supplier" id="add_supplier" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required></select>
                        </div>
                         <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Stock</label>
                            <input type="number" name="stock" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                            <input type="number" name="price" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Image</label>
                            <input type="file" name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200 rounded-b">
                <button type="button" id="btn-save-add" class="text-white bg-pink-600 hover:bg-pink-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="edit-product-modal">
    <div class="relative w-full max-w-2xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Edit Product</h3>
                <button type="button" onclick="closeModal('edit-product-modal')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">X</button>
            </div>
            <div class="p-6">
                <form id="edit-product-form" enctype="multipart/form-data">
                    <input type="hidden" name="id_product" id="edit_id">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                            <input type="text" name="name" id="edit_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">SKU</label>
                            <input type="text" name="sku" id="edit_sku" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                            <select name="id_category" id="edit_category" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required></select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Supplier</label>
                            <select name="id_supplier" id="edit_supplier" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required></select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Stock</label>
                            <input type="number" name="stock" id="edit_stock" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                            <input type="number" name="price" id="edit_price" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Image (Optional)</label>
                            <input type="file" name="image" id="edit_image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200 rounded-b">
                <button type="button" id="btn-save-edit" class="text-white bg-pink-600 hover:bg-pink-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="delete-product-modal">
    <div class="relative w-full max-w-md px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex justify-end p-2">
                <button type="button" onclick="closeModal('delete-product-modal')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-6 pt-0 text-center">
                <svg class="w-20 h-20 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                
                <h3 class="text-xl font-normal text-gray-500 mt-5 mb-6">Are you sure you want to delete this product?</h3>
                
                <button id="btn-confirm-delete" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2">
                    Yes, I'm sure
                </button>
                <button type="button" onclick="closeModal('delete-product-modal')" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center">
                    No, cancel
                </button>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>