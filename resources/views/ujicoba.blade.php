<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mile Cafe 32</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>
<body>
    @foreach ($menus as $menu)
        <div class="menu-box">
            <div class="menu-image">
                <img src="{{ asset($menu['image']) }}" alt="{{ $menu['title'] }}">
            </div>
            <div class="menu-description">
                <p class="title">{{ $menu['title'] }}</p>
                <p class="description">{{ $menu['description'] }}</p>
                <div class="price-controls">
                    <p class="price">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
                    <!-- Tombol Add dan Kontrol Jumlah -->
                    <button class="add-btn">Add</button>
                    <div class="quantity-controls" style="display: none;">
                        <button class="decrease-btn">−</button>
                        <span class="quantity">1</span>
                        <button class="increase-btn">+</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Tombol untuk menghapus Local Storage -->
    <button id="clear-storage-btn">Clear Local Storage</button>

    <!-- JavaScript untuk menangani perubahan jumlah dan menyimpan ke local storage -->
    <script>
        // Mengubah data menu ke dalam format JSON
        const menus = @json($menus); 
        let orderState = JSON.parse(localStorage.getItem('orders')) || []; // Mengambil pesanan dari Local Storage

        const addBtns = document.querySelectorAll('.add-btn');
        const quantityControlsList = document.querySelectorAll('.quantity-controls');
        const decreaseBtns = document.querySelectorAll('.decrease-btn');
        const increaseBtns = document.querySelectorAll('.increase-btn');
        const quantityDisplays = document.querySelectorAll('.quantity');
        const clearStorageBtn = document.getElementById('clear-storage-btn');

        // Fungsi untuk menyimpan state pesanan ke local storage
        function saveToLocalStorage() {
            localStorage.setItem('orders', JSON.stringify(orderState));
        }

        addBtns.forEach((addBtn, index) => {
            let quantity = 1;

            addBtn.addEventListener('click', () => {
                addBtn.style.display = 'none';
                quantityControlsList[index].style.display = 'flex';

                // Update order state
                const existingOrderIndex = orderState.findIndex(order => order.title === menus[index].title);
                
                if (existingOrderIndex >= 0) {
                    // Jika sudah ada, tambahkan kuantitas
                    orderState[existingOrderIndex].quantity++;
                } else {
                    // Jika belum ada, tambahkan item baru
                    const menuItem = {
                        title: menus[index].title,
                        quantity: 1,
                        price: menus[index].price
                    };
                    orderState.push(menuItem);
                }
                
                // Simpan ke local storage
                saveToLocalStorage();
                updateQuantityDisplay(index, orderState[existingOrderIndex]?.quantity || 1);
            });

            decreaseBtns[index].addEventListener('click', () => {
                const existingOrderIndex = orderState.findIndex(order => order.title === menus[index].title);
                if (existingOrderIndex >= 0) {
                    if (orderState[existingOrderIndex].quantity > 1) {
                        // Kurangi kuantitas
                        orderState[existingOrderIndex].quantity--;
                        updateQuantityDisplay(index, orderState[existingOrderIndex].quantity);
                    } else {
                        // Jika kuantitas mencapai 1 dan tombol - ditekan, hapus item dari order
                        orderState.splice(existingOrderIndex, 1);
                        quantityControlsList[index].style.display = 'none';
                        addBtn.style.display = 'inline-block';
                    }
                }
                // Simpan ke local storage
                saveToLocalStorage();
            });

            increaseBtns[index].addEventListener('click', () => {
                const existingOrderIndex = orderState.findIndex(order => order.title === menus[index].title);
                if (existingOrderIndex >= 0) {
                    // Tambah kuantitas
                    orderState[existingOrderIndex].quantity++;
                    updateQuantityDisplay(index, orderState[existingOrderIndex].quantity);
                }
                // Simpan ke local storage
                saveToLocalStorage();
            });
        });

        function updateQuantityDisplay(index, quantity) {
            quantityDisplays[index].textContent = quantity;
        }

        // Event listener untuk tombol Clear Local Storage
        clearStorageBtn.addEventListener('click', () => {
            localStorage.removeItem('orders'); // Menghapus data 'orders' dari Local Storage
            orderState = []; // Mengosongkan array orderState
            alert('Local Storage cleared!'); // Menampilkan pesan konfirmasi
        });
    </script>
</body>
</html>
