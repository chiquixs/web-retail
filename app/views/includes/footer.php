<footer class="footer-section">
	<div class="container relative">
		<div class="row g-5 mb-5">
			<div class="col-lg-4">
				<div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Furni<span>.</span></a></div>
				<p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant</p>

				<ul class="list-unstyled custom-social">
					<li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
					<li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
					<li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
					<li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
				</ul>
			</div>

			<div class="col-lg-8">
				<div class="row links-wrap">
					<div class="col-6 col-sm-6 col-md-3">
						<ul class="list-unstyled">
							<li><a href="#">About us</a></li>
							<li><a href="#">Contact us</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<!-- End Footer Section -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/tiny-slider.js"></script>
<script src="assets/js/custom.js"></script>

<?php 
// ✅ Load cart_logic.js hanya untuk halaman shop dan home
$currentPage = $_GET['page'] ?? 'home';
if (in_array($currentPage, ['shop', 'home'])): 
?>
<script src="assets/js/cart_logic.js"></script>
<?php endif; ?>

<?php 
// ✅ Load checkout_process.js HANYA untuk halaman cart
if ($currentPage === 'cart'): 
?>
<script src="assets/js/checkout_process.js"></script>
<?php endif; ?>

</body>
</html>