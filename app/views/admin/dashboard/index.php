<?php require_once __DIR__ . '/../../cms/includes/header.php'; ?>
<?php require_once __DIR__ . '/../../cms/includes/sidebar.php'; ?>

<div class="p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Total Products -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800">
                        <?= $data['totalProducts'] ?>
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Card Total Customers -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Total Customers</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800">
                        <?= $data['totalCustomers'] ?>
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Card Total Orders -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800">
                        <?= $data['totalTransactions'] ?>
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Total Supplier -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Total Suppliers</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800">
                        <?= $data['totalSuppliers'] ?>
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Total Categories -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium">Total Categories</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800">
                        <?= $data['totalCategories'] ?>
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"></path>
                    </svg>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../cms/includes/footer.php'; ?>
