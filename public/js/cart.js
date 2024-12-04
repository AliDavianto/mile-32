// Get data from localStorage
let orderState = JSON.parse(localStorage.getItem('pesanan')) || [];
let nomorMeja = localStorage.getItem('nomor_meja') || 'Unknown';
let totalHarga = JSON.parse(localStorage.getItem('total_harga')) || 0;

// Render cart items
function renderCartItems() {
    const cartContainer = document.getElementById('cart-items');
    const totalPriceDisplay = document.getElementById('total-amount');
    cartContainer.innerHTML = ''; // Clear previous items

    if (orderState.length === 0) {
        cartContainer.innerHTML = '<p>Your cart is empty.</p>';
        totalPriceDisplay.textContent = 'Rp0';
        return;
    }

    let totalHarga = 0;

    orderState.forEach(order => {
        const menuItem = menus.find(menu => menu.id_menu === order.id_menu);
        if (menuItem) {
            const itemTotal = menuItem.harga * order.kuantitas;
            totalHarga += itemTotal;

            const cartItem = document.createElement('div');
            cartItem.classList.add('menu-box');
            cartItem.innerHTML = `
                <div class="menu-image">
                    <img src="${menuItem.gambar}" alt="${menuItem.nama_produk}">
                </div>
                <div class="menu-description">
                    <p class="title">${menuItem.nama_produk} (x${order.kuantitas})</p>
                    <p class="description">${menuItem.deskripsi}</p>
                    <div class="price-controls">
                        <p class="price">Rp ${itemTotal.toLocaleString('id-ID')}</p>
                    </div>
                </div>
            `;
            cartContainer.appendChild(cartItem);
        }
    });

    totalPriceDisplay.textContent = `Rp ${totalHarga.toLocaleString('id-ID')}`;
}

// Handle checkout
async function handleCheckout() {
    if (orderState.length === 0) {
        alert('Your cart is empty. Please add items before checking out.');
        return;
    }
    const paymentMethod = document.getElementById('payment-method').value;
    // Refresh data from localStorage in case it was modified elsewhere
    const payload = {
        nomor_meja: localStorage.getItem('nomor_meja') || 'Unknown',
        pesanan: JSON.parse(localStorage.getItem('pesanan')) || [],
        total_harga: JSON.parse(localStorage.getItem('total_harga')) || 0,
        metode_pembayaran: paymentMethod, // Add the payment method key
    };

    try {
        const response = await fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(payload), // Ensure this includes the correct data
        });

        if (!response.ok) {
            throw new Error('Failed to process checkout.');
        }

        const result = await response.json();

        if (result.success) {
            alert(`Pesanan berhasil dibuat!`);
            // Clear localStorage and state
            localStorage.removeItem('pesanan');
            localStorage.removeItem('nomor_meja');
            localStorage.removeItem('total_harga');
            orderState = [];
            renderCartItems();
        } else {
            alert(`${result.message}`);
        }
    } catch (error) {
        console.error('Error during checkout:', error);
        alert('An error occurred during checkout. Please try again.');
    }
}


// Render cart items on page load
renderCartItems();
