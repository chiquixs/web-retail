<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="pt-6 px-4 w-full">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Category Management</h3>
            <!-- ✅ FIXED: Hanya gunakan data-modal-target, bukan data-modal-toggle -->
            <button data-modal-target="add-category-modal"
                class="text-white bg-pink-600 hover:bg-pink-700 focus:ring-4 focus:ring-pink-200 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Category
            </button>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($data['categories'])): ?>
                        <tr>
                            <td colspan="3" class="p-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2">No categories found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['categories'] as $cat): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 text-sm font-medium text-gray-900"><?= htmlspecialchars($cat['name']) ?></td>
                                <td class="p-3 text-sm text-gray-500"><?= htmlspecialchars($cat['description'] ?? '-') ?></td>
                                <td class="p-3 text-sm">
                                    <div class="flex items-center gap-2">
                                        <!-- ✅ FIXED: Hapus semua data-modal-* dari tombol table -->
                                        <button class="edit-category-btn bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors"
                                            data-id="<?= $cat['id_category'] ?>"
                                            data-name="<?= htmlspecialchars($cat['name']) ?>"
                                            data-description="<?= htmlspecialchars($cat['description'] ?? '') ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button class="delete-category-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors"
                                            data-id="<?= $cat['id_category'] ?>">
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
    </div>
</div>

<!-- ==================== ADD CATEGORY MODAL ==================== -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="add-category-modal">
    <div class="relative w-full max-w-xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Add Category</h3>
                <!-- ✅ Close button dengan data-modal-toggle -->
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="add-category-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <form id="add-category-form">
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Category Name *</label>
                        <input type="text" name="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Description</label>
                        <textarea name="description" rows="3" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"></textarea>
                    </div>
                </form>
            </div>
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <button type="button" id="btn-save-add-category" class="text-white bg-pink-600 hover:bg-pink-700 focus:ring-4 focus:ring-pink-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                    Add Category
                </button>
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 text-center transition-colors" data-modal-toggle="add-category-modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== EDIT CATEGORY MODAL ==================== -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="edit-category-modal">
    <div class="relative w-full max-w-xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Edit Category</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="edit-category-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <form id="edit-category-form">
                    <input type="hidden" name="id_category" id="edit_category_id">
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Category Name *</label>
                        <input type="text" name="name" id="edit_category_name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Description</label>
                        <textarea name="description" id="edit_category_description" rows="3" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"></textarea>
                    </div>
                </form>
            </div>
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <button type="button" id="btn-save-edit-category" class="text-white bg-pink-600 hover:bg-pink-700 focus:ring-4 focus:ring-pink-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                    Update Category
                </button>
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 text-center transition-colors" data-modal-toggle="edit-category-modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== DELETE CONFIRMATION MODAL ==================== -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="delete-category-modal">
    <div class="relative w-full max-w-md px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex justify-end p-2">
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="delete-category-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 pt-0 text-center">
                <svg class="w-20 h-20 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-normal text-gray-500 mt-5 mb-6">Are you sure you want to delete this category?</h3>
                <button id="btn-confirm-delete-category" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 transition-colors">
                    Yes, I'm sure
                </button>
                <button type="button" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center transition-colors" data-modal-toggle="delete-category-modal">
                    No, cancel
                </button>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>