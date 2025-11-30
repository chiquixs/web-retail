(function() {
	'use strict';

	var tinyslider = function() {
		var el = document.querySelectorAll('.testimonial-slider');

		if (el.length > 0) {
			var slider = tns({
				container: '.testimonial-slider',
				items: 1,
				axis: "horizontal",
				controlsContainer: "#testimonial-nav",
				swipeAngle: false,
				speed: 700,
				nav: true,
				controls: true,
				autoplay: true,
				autoplayHoverPause: true,
				autoplayTimeout: 3500,
				autoplayButtonOutput: false
			});
		}
	};
	tinyslider();

	


	var sitePlusMinus = function() {

		var value,
    		quantity = document.getElementsByClassName('quantity-container');

		function createBindings(quantityContainer) {
	      var quantityAmount = quantityContainer.getElementsByClassName('quantity-amount')[0];
	      var increase = quantityContainer.getElementsByClassName('increase')[0];
	      var decrease = quantityContainer.getElementsByClassName('decrease')[0];
	      increase.addEventListener('click', function (e) { increaseValue(e, quantityAmount); });
	      decrease.addEventListener('click', function (e) { decreaseValue(e, quantityAmount); });
	    }

	    function init() {
	        for (var i = 0; i < quantity.length; i++ ) {
						createBindings(quantity[i]);
	        }
	    };

	    function increaseValue(event, quantityAmount) {
	        value = parseInt(quantityAmount.value, 10);

	        console.log(quantityAmount, quantityAmount.value);

	        value = isNaN(value) ? 0 : value;
	        value++;
	        quantityAmount.value = value;
	    }

	    function decreaseValue(event, quantityAmount) {
	        value = parseInt(quantityAmount.value, 10);

	        value = isNaN(value) ? 0 : value;
	        if (value > 0) value--;

	        quantityAmount.value = value;
	    }
	    
	    init();
		
	};
	sitePlusMinus();


})()

// File: public/assets/js/custom.js (Tambahkan ini)

function updateCartBadge(newCount) {
    let badge = document.getElementById('cart-count');
    let cartLink = document.querySelector('a[href="index.php?page=cart"]');

    if (!cartLink) return; // Keluar jika link cart tidak ditemukan

    if (newCount > 0) {
        if (!badge) {
            // Jika badge belum ada, buat badge baru
            badge = document.createElement('span');
            badge.id = 'cart-count';
            badge.className = 'cart-badge'; // Pastikan CSS .cart-badge sudah ada di style.css
            cartLink.appendChild(badge);
        }
        badge.innerText = newCount;
    } else if (badge) {
        // Jika count 0, hapus badge
        badge.remove();
    }
}

// File: public/assets/js/custom.js (Perhatikan bagian AJAX)

async function ajaxAddToCart(event, productId, productName, productPrice) {
    event.preventDefault();
    
    const formData = new FormData();
    // Kirim semua data yang dibutuhkan oleh CartController (id, name, price, qty)
    formData.append('id_product', productId); // Gunakan id_product
    formData.append('name', productName);
    formData.append('price', productPrice);
    formData.append('qty', 1); // Tambah 1 per klik

    try {
        // Kirim permintaan via POST
        const response = await fetch('index.php?page=add_to_cart', {
            method: 'POST', 
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            updateCartBadge(data.cart_count); // Fungsi update badge dari JS sebelumnya
            console.log('Produk ditambahkan! Total:', data.cart_count);
        } else {
            alert('Gagal menambah cart: ' + data.message);
        }
    } catch (error) {
        console.error('Error AJAX:', error);
    }
}

