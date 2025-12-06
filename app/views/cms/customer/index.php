<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="pt-6 px-4 w-full">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Customer Management</h3>
            <button data-modal-target="add-customer-modal" data-modal-toggle="add-customer-modal"
                class="text-white bg-pink-600 hover:bg-pink-700 focus:ring-4 focus:ring-pink-200 font-medium rounded-lg text-sm px-4 py-2 inline-flex items-center">
                Add Customer
            </button>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                        <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    <?php if (empty($data['customers'])): ?>
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">No customers found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['customers'] as $c): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="p-3 text-sm"><?= htmlspecialchars($c['name']) ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($c['email']) ?></td>
                                <td class="p-3 text-sm text-gray-500"><?= htmlspecialchars($c['address'] ?? '-') ?></td>

                                <td class="p-3 text-sm">
                                    <button class="edit-customer-btn bg-pink-600 text-white px-3 py-2 rounded text-xs mr-2"
                                        data-modal-target="edit-customer-modal"
                                        data-modal-toggle="edit-customer-modal"
                                        data-id="<?= $c['id_customer'] ?>"
                                        data-name="<?= htmlspecialchars($c['name']) ?>"
                                        data-email="<?= htmlspecialchars($c['email']) ?>"
                                        data-address="<?= htmlspecialchars($c['address'] ?? '') ?>">
                                        Edit
                                    </button>

                                    <button class="delete-customer-btn bg-red-600 text-white px-3 py-2 rounded text-xs"
                                        data-id="<?= $c['id_customer'] ?>">
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

<!-- ✅ Add Customer Modal -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="add-customer-modal">
    <div class="relative w-full max-w-xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Add Customer</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="add-customer-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <form id="add-customer-form">
                    <div class="mb-4">
                        <label class="text-sm font-medium">Name *</label>
                        <input type="text" name="name" class="shadow-sm bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                    </div>

                    <div class="mb-4">
                        <label class="text-sm font-medium">Email *</label>
                        <input type="email" name="email" class="shadow-sm bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                    </div>

                    <div class="mb-4">
                        <label class="text-sm font-medium">Address</label>
                        <textarea name="address" rows="3" class="shadow-sm bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"></textarea>
                    </div>
                </form>
            </div>

            <div class="flex items-center p-6 space-x-2 border-t rounded-b">
                <button id="btn-save-add-customer" type="button" class="text-white bg-pink-600 hover:bg-pink-700 rounded-lg text-sm px-5 py-2.5">
                    Add Customer
                </button>

                <button type="button" data-modal-toggle="add-customer-modal"
                    class="text-gray-600 bg-white hover:bg-gray-100 border rounded-lg text-sm px-5 py-2.5">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Edit Customer Modal -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="edit-customer-modal">
    <div class="relative w-full max-w-xl px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">

            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Edit Customer</h3>
                <button type="button" class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5" data-modal-toggle="edit-customer-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <form id="edit-customer-form">

                    <input type="hidden" name="id_customer" id="edit_customer_id">

                    <div class="mb-4">
                        <label class="text-sm font-medium">Name *</label>
                        <input type="text" name="name" id="edit_customer_name" class="shadow-sm bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                    </div>

                    <div class="mb-4">
                        <label class="text-sm font-medium">Email *</label>
                        <input type="email" name="email" id="edit_customer_email" class="shadow-sm bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                    </div>

                    <div class="mb-4">
                        <label class="text-sm font-medium">Address</label>
                        <textarea name="address" id="edit_customer_address" rows="3" class="shadow-sm bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"></textarea>
                    </div>

                </form>
            </div>

            <div class="flex items-center p-6 space-x-2 border-t rounded-b">
                <button id="btn-save-edit-customer" type="button" class="text-white bg-pink-600 hover:bg-pink-700 rounded-lg text-sm px-5 py-2.5">
                    Update Customer
                </button>

                <button type="button" data-modal-toggle="edit-customer-modal"
                    class="text-gray-600 bg-white hover:bg-gray-100 border rounded-lg text-sm px-5 py-2.5">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Delete Customer Modal -->
<div class="hidden overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full" id="delete-customer-modal">
    <div class="relative w-full max-w-md px-4 h-full md:h-auto">
        <div class="bg-white rounded-lg shadow relative">

            <div class="flex justify-end p-2">
                <button class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5" data-modal-toggle="delete-customer-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 pt-0 text-center">
                <svg class="w-20 h-20 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>

                <h3 class="text-xl text-gray-500 mt-5 mb-6">Are you sure you want to delete this customer?</h3>

                <button id="btn-confirm-delete-customer" class="text-white bg-red-600 hover:bg-red-800 rounded-lg text-base px-3 py-2.5 mr-2">
                    Yes, I'm sure
                </button>

                <button data-modal-toggle="delete-customer-modal"
                    class="text-gray-900 bg-white hover:bg-gray-100 border rounded-lg text-base px-3 py-2.5">
                    No, cancel
                </button>
            </div>

        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>