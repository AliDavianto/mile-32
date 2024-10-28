// Get data from localStorage
let orderState = JSON.parse(localStorage.getItem('pesanan')) || [];
let nomorMeja = JSON.parse(localStorage.getItem('nomor_meja'));
let totalHarga = JSON.parse(localStorage.getItem('total_harga'));
// Function to render cart items
function renderCartItems() {
    const cartContainer = document.getElementById('cart-items');
    const totalPriceDisplay = document.getElementById('total-price');

    // Clear previous cart items
    cartContainer.innerHTML = '';

    let totalHarga = 0;

    // Check if orderState is empty
    if (orderState.length === 0) {
        cartContainer.innerHTML = '<p>Your cart is empty.</p>';
        return;
    }

    // Filter menus to find matching cart items
    orderState.forEach(order => {
        const menuItem = menus.find(menu => menu.id_menu === order.id_menu);
        if (menuItem) {
            const itemTotal = menuItem.harga * order.kuantitas;
            totalHarga += itemTotal;

            const cartItem = document.createElement('div');
            cartItem.classList.add('menu-box');

            cartItem.innerHTML = `
                <div class="menu-image">
                    <img src="${menuItem.image || 'miegoreng.png'}" alt="${menuItem.nama_produk}">
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

    totalPriceDisplay.innerHTML = `<h3>Total Price: Rp ${totalHarga.toLocaleString('id-ID')}</h3>`;
}

// Call the function to render cart items
renderCartItems();

// Clear Local Storage Button functionality
document.getElementById('clear-storage-btn').addEventListener('click', () => {
    localStorage.removeItem('pesanan');
    orderState = [];
    renderCartItems();
});

// Function to handle checkout
async function handleCheckout() {
    let orderState = JSON.parse(localStorage.getItem('pesanan')) || [];

    if (orderState.length === 0) {
        alert('Your cart is empty. Please add items before checking out.');
        return;
    }

    const payload = {
        nomor_meja : nomorMeja,
        pesanan: orderState,
        total_harga : totalHarga
    };

    try {
        const response = await fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json();

        // if (result.success) {
        //     alert('Checkout successful!');
        //     // localStorage.removeItem('pesanan'); // Clear the cart on success
        //     renderCartItems(); // Re-render cart to reflect changes
        // } else {
        //     alert('Checkout failed: ' + result.message);
        // }
    } catch (error) {
        console.error('Error during checkout:', error);
        alert('An error occurred during checkout. Please try again.');
    }
}

// Add event listener to the checkout button only if it exists
const checkoutButton = document.getElementById('checkout-btn');
if (checkoutButton) {
    checkoutButton.addEventListener('click', handleCheckout);
}
