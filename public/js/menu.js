let orderState = JSON.parse(localStorage.getItem('pesanan')) || [];

// Assuming menus is already defined somewhere in your code
const quantityControlsList = document.querySelectorAll('.quantity-controls');
const decreaseBtns = document.querySelectorAll('.decrease-btn');
const increaseBtns = document.querySelectorAll('.increase-btn');
const quantityDisplays = document.querySelectorAll('.quantity');
const clearStorageBtn = document.getElementById('clear-storage-btn');
const nomorMeja = 17; // Replace with the actual table number if dynamic

// Function to calculate total price
function calculateTotalPrice(menu) {
    return menu.reduce((total, item) => total + (item.harga * item.quantity), 0);
}

// Save order state to local storage
function saveToLocalStorage() {
    // Filter out items with quantity 0 before saving
    const filteredOrderState = orderState.filter(item => item.quantity > 0);

    // Map the items to a new array with the desired key
    const pesanan = filteredOrderState.map(item => ({
        id_menu: item.id_menu,
        kuantitas: item.quantity,
        harga: item.harga
    }));

    const totalHarga = calculateTotalPrice(filteredOrderState);
    localStorage.setItem('nomor_meja', nomorMeja);
    localStorage.setItem('pesanan', JSON.stringify(pesanan));
    localStorage.setItem('total_harga', totalHarga);
}

// Update displayed quantity for a given index
function updateQuantityDisplay(index, quantity) {
    quantityDisplays[index].textContent = quantity;
    decreaseBtns[index].disabled = quantity <= 0; // Disable decrease button if quantity is 0
}

// Initialize quantity displays from local storage
function initializeQuantities() {
    menus.forEach((menu, index) => {
        const existingOrderIndex = orderState.findIndex(order => order.id_menu === menu.id_menu);
        if (existingOrderIndex >= 0) {
            const quantity = orderState[existingOrderIndex].quantity;
            updateQuantityDisplay(index, quantity);
        } else {
            updateQuantityDisplay(index, 0); // Set to 0 if not in orderState
        }
    });
}

// Decrease quantity or remove item
decreaseBtns.forEach((decreaseBtn, index) => {
    decreaseBtn.addEventListener('click', () => {
        const existingOrderIndex = orderState.findIndex(order => order.id_menu === menus[index].id_menu);
        if (existingOrderIndex >= 0 && orderState[existingOrderIndex].quantity > 0) {
            orderState[existingOrderIndex].quantity--;
            updateQuantityDisplay(index, orderState[existingOrderIndex].quantity);

            // If quantity becomes 0, remove the item from the orderState
            if (orderState[existingOrderIndex].quantity === 0) {
                orderState.splice(existingOrderIndex, 1); // Remove the item from orderState
            }
        }
        saveToLocalStorage();
    });
});

// Increase quantity
increaseBtns.forEach((increaseBtn, index) => {
    increaseBtn.addEventListener('click', () => {
        const existingOrderIndex = orderState.findIndex(order => order.id_menu === menus[index].id_menu);
        if (existingOrderIndex >= 0) {
            orderState[existingOrderIndex].quantity++;
            updateQuantityDisplay(index, orderState[existingOrderIndex].quantity);
        } else {
            // If the menu item was not previously in the orderState, add it
            orderState.push({
                id_menu: menus[index].id_menu,
                quantity: 1,
                harga: menus[index].harga,
            });
            updateQuantityDisplay(index, 1);
        }
        saveToLocalStorage();
    });
});

// Clear Local Storage button
clearStorageBtn.addEventListener('click', () => {
    localStorage.removeItem('nomor_meja');
    localStorage.removeItem('pesanan');
    localStorage.removeItem('total_harga');
    orderState = [];
    alert('Local Storage cleared!');

    // Reset all quantities to 0
    quantityDisplays.forEach((display, index) => {
        display.textContent = '0'; // Reset quantity display to 0
        quantityControlsList[index].style.display = 'flex'; // Show quantity controls
        decreaseBtns[index].disabled = true; // Disable decrease button
    });
});

// Initialize quantities on page load
initializeQuantities();
