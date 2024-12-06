document.addEventListener("DOMContentLoaded", () => {
    const kategoriContainers = {
        1: document.querySelector("#kategori-1 .menu-container"), // Makanan
        2: document.querySelector("#kategori-2 .menu-container"), // Minuman
        4: document.querySelector("#kategori-4 .menu-container"), // Snack
    };

    // Function to render menus
    function renderMenus(filteredMenus) {
        // Clear all categories
        Object.values(kategoriContainers).forEach(container => (container.innerHTML = ""));

        // Render menus based on the filteredMenus array
        filteredMenus.forEach(menu => {
            const menuBox = document.createElement("div");
            menuBox.classList.add("menu-box");

            menuBox.innerHTML = `
                <div class="menu-image">
                    <img src="${menu.gambar}" alt="${menu.nama_produk}">
                </div>
                <div class="menu-description">
                    <p class="title">${menu.nama_produk}</p>
                    <p class="description">${menu.deskripsi}</p>
                    <div class="price-controls">
                        <p class="price">Rp ${menu.harga.toLocaleString("id-ID")}</p>
                        <div class="quantity-controls" style="display: flex;">
                            <button class="decrease-btn" disabled>−</button>
                            <span class="quantity">0</span>
                            <button class="increase-btn">+</button>
                        </div>
                    </div>
                </div>
            `;

            // Append to the corresponding category container
            if (kategoriContainers[menu.id_kategori]) {
                kategoriContainers[menu.id_kategori].appendChild(menuBox);
            }
        });
    }

    // Initial render of all menus
    renderMenus(menus);

    // Search functionality
    const searchInput = document.getElementById("search-input");
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();

        // Filter menus based on search query
        const filteredMenus = menus.filter(menu =>
            menu.nama_produk.toLowerCase().includes(query)
        );

        // Re-render menus with filtered results
        renderMenus(filteredMenus);
    });

    // Reinitialize quantity controls after rendering menus
    initializeQuantities();
});

let orderState = JSON.parse(localStorage.getItem("pesanan")) || [];
const nomorMeja = 17;

// Save order state to local storage
function saveToLocalStorage() {
    const filteredOrderState = orderState.filter(item => item.quantity > 0);
    const pesanan = filteredOrderState.map(item => ({
        id_menu: item.id_menu,
        kuantitas: item.quantity,
        harga: item.harga,
    }));
    const totalHarga = calculateTotalPrice(filteredOrderState);

    localStorage.setItem("nomor_meja", nomorMeja);
    localStorage.setItem("pesanan", JSON.stringify(pesanan));
    localStorage.setItem("total_harga", totalHarga);
}

// Calculate total price
function calculateTotalPrice(menu) {
    return menu.reduce((total, item) => total + item.harga * item.quantity, 0);
}

// Update displayed quantity
function updateQuantityDisplay(index, quantity) {
    const quantityDisplays = document.querySelectorAll(".quantity");
    const decreaseBtns = document.querySelectorAll(".decrease-btn");
    quantityDisplays[index].textContent = quantity;
    decreaseBtns[index].disabled = quantity <= 0;
}

// Initialize quantities
function initializeQuantities() {
    menus.forEach((menu, index) => {
        const existingOrder = orderState.find(order => order.id_menu === menu.id_menu);
        const quantity = existingOrder ? existingOrder.kuantitas : 0;
        updateQuantityDisplay(index, quantity);
    });
}

// Add event listeners for quantity buttons
document.addEventListener("click", event => {
    if (event.target.classList.contains("increase-btn")) {
        const index = Array.from(document.querySelectorAll(".increase-btn")).indexOf(event.target);
        const existingOrderIndex = orderState.findIndex(order => order.id_menu === menus[index].id_menu);
        if (existingOrderIndex >= 0) {
            orderState[existingOrderIndex].quantity++;
        } else {
            orderState.push({
                id_menu: menus[index].id_menu,
                quantity: 1,
                harga: menus[index].harga,
            });
        }
        updateQuantityDisplay(index, orderState.find(order => order.id_menu === menus[index].id_menu).quantity);
        saveToLocalStorage();
    }

    if (event.target.classList.contains("decrease-btn")) {
        const index = Array.from(document.querySelectorAll(".decrease-btn")).indexOf(event.target);
        const existingOrderIndex = orderState.findIndex(order => order.id_menu === menus[index].id_menu);
        if (existingOrderIndex >= 0 && orderState[existingOrderIndex].quantity > 0) {
            orderState[existingOrderIndex].quantity--;
            if (orderState[existingOrderIndex].quantity === 0) {
                orderState.splice(existingOrderIndex, 1);
            }
            updateQuantityDisplay(index, orderState.find(order => order.id_menu === menus[index].id_menu)?.quantity || 0);
            saveToLocalStorage();
        }
    }
});
