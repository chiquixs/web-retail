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

    <!-- REFRESH MV Penjualan Harian (Mengambil lebar penuh) -->
    <div class="mb-6">
        <a href="index.php?page=admin_refresh_mv_daily_sales_summary" 
        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
            🔄 Refresh Chart Penjualan Harian
        </a>
    </div>
    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Bagian 2: Chart Penjualan Harian (Mengambil lebar penuh) -->
    <div class="bg-white p-6 rounded-lg shadow-xl border border-gray-200 mb-8 w-full">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">GRAFIK PENJUALAN HARIAN</h2>
        <canvas id="dailySalesChart"></canvas> 
    </div>

    
    <!-- REFRESH MV Tabel Best Selling Products -->
    <div class="mb-6">
        <a href="index.php?page=admin_refresh_mv_best_selling_products" 
        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
        🔄 Refresh Data Produk Terlaris
    </a>
</div>
<?php if (!empty($_SESSION['best_selling_refresh_success'])): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            <?= $_SESSION['best_selling_refresh_success']; unset($_SESSION['best_selling_refresh_success']); ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['best_selling_refresh_error'])): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <?= $_SESSION['best_selling_refresh_error']; unset($_SESSION['best_selling_refresh_error']); ?>
            </div>
            <?php endif; ?>
            
    <!-- Bagian 4: Tabel View Best Selling Products -->
    <style>
        .pretty-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            font-family: 'Segoe UI', sans-serif;
        }
        .pretty-table thead {
            background: #2196f3;
            color: white;
            font-weight: bold;
        }
        .pretty-table thead{
            background: #218ef3ff;
            color: white;
            font-weight: bold;
        }
        .pretty-table th,
        .pretty-table td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .pretty-table tbody tr:hover {
            background: #f2f9ff;
        }
        .pretty-table tbody tr:last-child td {
            border-bottom: none;
        }
        .refresh-btn {
            background: #2196f3;
            border: none;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            font-weight: 600;
        }
        .refresh-btn:hover {
            background: #0b7dda;
            
        }
        </style>
        <div class="pretty-table-wrapper">
            <h3 style="color:black; font-weight: bold; margin-bottom: 10px;">🏆 PRODUK TERLARIS</h3>
            <table class="pretty-table">
                <thead>
                    <tr>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Total Terjual</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bestSelling as $p): ?>
                        <tr>
                            <td><?= $p['id_product'] ?></td>
                            <td><?= $p['product'] ?></td>
                            <td><?= $p['category'] ?></td>
                            <td><?= $p['supplier'] ?></td>
                            <td><?= $p['total_sold'] ?></td>
                            <td>Rp <?= number_format($p['revenue_generated'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Bagian 3: Profit Loss -->
            <div class="pretty-table-wrapper">
                <h3 style="color:black; font-weight: bold; margin-bottom: 10px;">⚖️ LABA RUGI</h3>
                <table class="pretty-table">
                    <thead>
                        <tr>
                            <th>Tanggal Penjualan</th>
                            <th>Total Pendapatan Kotor</th>
                            <th>Total Modal (HPP)</th>
                            <th>Laba Kotor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($profitLoss as $p): ?>
                            <tr>
                                <td><?= $p['sales_date'] ?></td>
                                <td>Rp <?= number_format($p['total_revenue'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($p['estimated_cost'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($p['gross_profit'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

            <!-- Bagian 5: STOCK MONITOR -->
            <div class="pretty-table-wrapper">
                <h3 style="color:black; font-weight: bold; margin-bottom: 10px;">📦 STOK PRODUK</h3>
                <h2 style="color:black; font-weight: bold; margin-bottom: 10px;">Notes: Low <= 20, Middle > 20, High >= 70</h2>
                <table class="pretty-table">
                    <thead>
                        <tr>
                            <th>ID Product</th>
                            <th>SKU</th>
                            <th>Produk</th>
                            <th>ID Category</th>
                            <th>ID Supplier</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stockMonitor as $p): ?>
                            <tr>
                                <td><?= $p['id_product'] ?></td>
                                <td><?= $p['sku'] ?></td>
                                <td><?= $p['name'] ?></td>
                                <td><?= $p['id_category'] ?></td>
                                <td><?= $p['id_supplier'] ?></td>
                                <td><?= $p['stock'] ?></td>
                                <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                <td><?= $p['stock_status'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bagian 6: HISTORY TRANSACTIONS -->
            <div class="pretty-table-wrapper">
                <h3 style="color:black; font-weight: bold; margin-bottom: 10px;">📝 HISTORY TRANSAKSI PELANGGAN</h3>
                <table class="pretty-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Transaksi</th>
                            <th>Pelanggan</th>
                            <th>ID Produk</th>
                            <th>Produk</th>
                            <th>Kuantitas</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $p): ?>
                            <tr>
                                <td><?= $p['tx_date'] ?></td>
                                <td><?= $p['id_transaction'] ?></td>
                                <td><?= $p['customer'] ?></td>
                                <td><?= $p['id_product'] ?></td>
                                <td><?= $p['product'] ?></td>
                                <td><?= $p['qty'] ?></td>
                                <td>Rp <?= number_format($p['product_price'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($p['subtotal'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($p['total_amount'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

</div>
<?php require_once __DIR__ . '/../../cms/includes/footer.php'; ?>
