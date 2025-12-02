<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="pt-6 px-4 w-full">
    <div class="bg-white shadow rounded-lg p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Product Management</h3>
            <button data-modal-target="add-product-modal" data-modal-toggle="add-product-modal"
                class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-4 py-2 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </button>
        </div>

        <!-- Search Bar & Info -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text"
                        id="searchInput"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full pl-10 p-2.5"
                        placeholder="Search by product, SKU, category, or supplier..."
                        value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                </div>
            </div>
            <div class="ml-4">
                <span id="showingInfo" class="text-sm text-gray-600">
                    Showing <?= $data['pagination']['from'] ?> to <?= $data['pagination']['to'] ?>
                    of <?= $data['pagination']['total_records'] ?> entries
                </span>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="hidden">
            <div class="flex items-center justify-center py-8">
                <svg class="animate-spin h-8 w-8 text-cyan-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-3 text-gray-600">Loading...</span>
            </div>
        </div>

        <!-- Products Table -->
        <div class="w-full overflow-x-auto" id="tableContainer">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="bg-white divide-y divide-gray-200">
                    <?php if (empty($data['products'])): ?>
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="mt-2">No products found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['products'] as $p): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3">
                                    <img src="/web-retail-rev/public/assets/images/products/<?= htmlspecialchars($p['image']) ?>"
                                        class="w-16 h-16 rounded object-cover"
                                        alt="<?= htmlspecialchars($p['product_name']) ?>">
                                </td>
                                <td class="p-3">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($p['product_name']) ?></div>
                                </td>
                                <td class="p-3">
                                    <span class="text-sm text-gray-600"><?= htmlspecialchars($p['sku']) ?></span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($p['category_name']) ?>
                                    </span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                        <?= htmlspecialchars($p['supplier_name']) ?>
                                    </span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded <?= $p['stock'] > 10 ? 'bg-green-100 text-green-800' : ($p['stock'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= $p['stock'] ?>
                                    </span>
                                </td>
                                <td class="p-3">
                                    <span class="text-sm font-semibold text-gray-900">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center space-x-2">
                                        <button class="edit-product-btn bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-1.5 rounded text-xs font-medium transition"
                                            data-modal-target="edit-product-modal"
                                            data-modal-toggle="edit-product-modal"
                                            data-id="<?= $p['id_product'] ?>"
                                            data-name="<?= htmlspecialchars($p['product_name']) ?>"
                                            data-sku="<?= htmlspecialchars($p['sku']) ?>"
                                            data-price="<?= $p['price'] ?>"
                                            data-stock="<?= $p['stock'] ?>"
                                            data-category-id="<?= $p['id_category'] ?>"
                                            data-supplier-id="<?= $p['id_supplier'] ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button class="delete-product-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition"
                                            data-id="<?= $p['id_product'] ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($data['pagination']['total_pages'] > 1): ?>
            <div class="mt-6" id="paginationContainer">
                <nav class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <!-- Mobile Pagination -->
                        <button onclick="loadPage(<?= $data['pagination']['current_page'] - 1 ?>)"
                            <?= !$data['pagination']['has_previous'] ? 'disabled' : '' ?>
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                            Previous
                        </button>
                        <button onclick="loadPage(<?= $data['pagination']['current_page'] + 1 ?>)"
                            <?= !$data['pagination']['has_next'] ? 'disabled' : '' ?>
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Page <span class="font-medium"><?= $data['pagination']['current_page'] ?></span>
                                of <span class="font-medium"><?= $data['pagination']['total_pages'] ?></span>
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <!-- Previous Button -->
                                <button onclick="loadPage(<?= $data['pagination']['current_page'] - 1 ?>)"
                                    <?= !$data['pagination']['has_previous'] ? 'disabled' : '' ?>
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Page Numbers -->
                                <?php
                                $start = max(1, $data['pagination']['current_page'] - 2);
                                $end = min($data['pagination']['total_pages'], $data['pagination']['current_page'] + 2);

                                for ($i = $start; $i <= $end; $i++):
                                ?>
                                    <button onclick="loadPage(<?= $i ?>)"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium <?= $i == $data['pagination']['current_page'] ? 'bg-cyan-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?>">
                                        <?= $i ?>
                                    </button>
                                <?php endfor; ?>

                                <!-- Next Button -->
                                <button onclick="loadPage(<?= $data['pagination']['current_page'] + 1 ?>)"
                                    <?= !$data['pagination']['has_next'] ? 'disabled' : '' ?>
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Add Product Modal -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="add-product-modal">
    <div class="relative w-full max-w-2xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Add Product</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="add-product-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <form id="add-product-form" method="post" enctype="multipart/form-data">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="product-name" class="text-sm font-medium text-gray-900 block mb-2">Product Name *</label>
                            <input type="text" name="name" id="product-name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="sku" class="text-sm font-medium text-gray-900 block mb-2">SKU *</label>
                            <input type="text" name="sku" id="sku" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="category" class="text-sm font-medium text-gray-900 block mb-2">Category *</label>
                            <select name="id_category" id="add_category" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="supplier" class="text-sm font-medium text-gray-900 block mb-2">Supplier *</label>
                            <select name="id_supplier" id="add_supplier" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                                <option value="">Select Supplier</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="stock" class="text-sm font-medium text-gray-900 block mb-2">Stock *</label>
                            <input type="number" name="stock" id="stock" min="0" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="price" class="text-sm font-medium text-gray-900 block mb-2">Price (Rp) *</label>
                            <input type="number" name="price" id="price" min="0" step="1000" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-full">
                            <label for="image" class="text-sm font-medium text-gray-900 block mb-2">Product Image *</label>
                            <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" required>
                            <p class="mt-1 text-sm text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                            <div id="image-preview" class="mt-3 hidden">
                                <img id="preview-img" src="" alt="Preview" class="max-w-xs h-48 object-cover rounded-lg border">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200 rounded-b">
                <button type="button" id="btn-save-add" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Add Product
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="edit-product-modal">
    <div class="relative w-full max-w-2xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Edit Product</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="edit-product-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <form id="edit-product-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_product" id="edit_id">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Product Name *</label>
                            <input type="text" name="name" id="edit_name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">SKU *</label>
                            <input type="text" name="sku" id="edit_sku" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Category *</label>
                            <select name="id_category" id="edit_category" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5" required>
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Supplier *</label>
                            <select name="id_supplier" id="edit_supplier" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5" required>
                                <option value="">Select Supplier</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Stock *</label>
                            <input type="number" name="stock" id="edit_stock" min="0" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Price (Rp) *</label>
                            <input type="number" name="price" id="edit_price" min="0" step="1000" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5" required>
                        </div>
                        <div class="col-span-full">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Product Image (Optional)</label>
                            <input type="file" name="image" id="edit_image" accept="image/png, image/jpeg, image/jpg" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200 rounded-b">
                <button type="button" id="btn-save-edit" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Update Product
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="delete-product-modal">
    <div class="relative w-full max-w-md px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex justify-end p-2">
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="delete-product-modal">
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
                <button type="button" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-product-modal">
                    No, cancel
                </button>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>