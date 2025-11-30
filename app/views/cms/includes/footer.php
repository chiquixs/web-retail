</main>
    </div>
  </div>

  <!-- Custom CSS untuk Backdrop Blur -->
  <style>
    [modal-backdrop],
    .fixed.inset-0.z-40 {
      background-color: rgba(0, 0, 0, 0.3) !important;
      backdrop-filter: blur(10px) !important;
      -webkit-backdrop-filter: blur(10px) !important;
    }
  </style>

  <!-- Flowbite JS -->
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
  
  <!-- Windster App JS -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="https://themewagon.github.io/windster/app.bundle.js"></script>
  
  <!-- Product Page Script -->
  <?php if (isset($_GET['page']) && $_GET['page'] === 'admin_product'): ?>
      <script src="../public/assets/js/admin/product.js"></script>
  <?php endif; ?>
  
  <!-- Category Page Script -->
  <?php if (isset($_GET['page']) && $_GET['page'] === 'admin_category'): ?>
      <script src="../public/assets/js/admin/category.js"></script>
  <?php endif; ?>
  
  <!-- Supplier Page Script -->
  <?php if (isset($_GET['page']) && $_GET['page'] === 'admin_supplier'): ?>
      <script src="../public/assets/js/admin/supplier.js"></script>
  <?php endif; ?>
</body>
</html>