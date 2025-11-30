<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="pt-6 px-4 w-full">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Product Management</h3>
            <button data-modal-target="add-product-modal" data-modal-toggle="add-product-modal" 
                    class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-4 py-2 inline-flex items-center">
                Add Product
            </button>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3">Image</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">SKU</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Supplier</th>
                        <th class="p-3">Stock</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($data['products'])): ?>
                        <tr>
                            <td colspan="8" class="p-4 text-center text-gray-500">No products found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['products'] as $p): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="p-3">
                                    <img src="/web-retail-rev/public/assets/images/products/<?= $p['image'] ?>" class="w-16 h-16 rounded">
                                </td>
                                <td class="p-3"><?= htmlspecialchars($p['product_name']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($p['sku']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($p['category_name']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($p['supplier_name']) ?></td>
                                <td class="p-3"><?= $p['stock'] ?></td>
                                <td class="p-3">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                <td class="p-3">
                                    <button class="edit-product-btn bg-cyan-600 text-white px-3 py-2 rounded text-xs"
                                        data-modal-target="edit-product-modal"
                                        data-modal-toggle="edit-product-modal"
                                        data-id="<?= $p['id_product'] ?>"
                                        data-name="<?= htmlspecialchars($p['product_name']) ?>"
                                        data-sku="<?= htmlspecialchars($p['sku']) ?>"
                                        data-price="<?= $p['price'] ?>"
                                        data-stock="<?= $p['stock'] ?>"
                                        data-category-id="<?= $p['id_category'] ?>"
                                        data-supplier-id="<?= $p['id_supplier'] ?>">
                                        Edit
                                    </button>
                                    <button class="delete-product-btn bg-red-600 text-white px-3 py-2 rounded text-xs"
                                        data-id="<?= $p['id_product'] ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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