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
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo-container">
            <img src="logo mile.png" alt="Logo Mile Cafe" class="logo-image"> <!-- Gambar logo -->
            <div class="logo-text">
                <h1>Miles 32</h1> <!-- Nama Cafe -->
                <p>Temukan harmoni rasa dan cerita di Miles 32, tempat membawa pengalaman baru!</p> <!-- Teks di bawah nama -->
            </div>
        </div>
    </div>
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
                    <button id="add-btn" class="add-btn">Add</button>
                    <div class="quantity-controls" id="quantity-controls" style="display: none;">
                        <button id="decrease-btn">−</button>
                        <span id="quantity">1</span>
                        <button id="increase-btn">+</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- JavaScript untuk menangani perubahan jumlah -->
    <script>
        const decreaseBtns = document.querySelectorAll('#decrease-btn');
        const increaseBtns = document.querySelectorAll('#increase-btn');
        const quantityDisplays = document.querySelectorAll('#quantity');
        const addBtns = document.querySelectorAll('#add-btn');
        const quantityControlsList = document.querySelectorAll('.quantity-controls');

        addBtns.forEach((addBtn, index) => {
            let quantity = 1;

            addBtn.addEventListener('click', () => {
                addBtn.style.display = 'none';
                quantityControlsList[index].style.display = 'flex';
            });

            decreaseBtns[index].addEventListener('click', () => {
                if (quantity > 1) {
                    quantity--;
                    quantityDisplays[index].textContent = quantity;
                } else if (quantity === 1) {
                    quantityControlsList[index].style.display = 'none';
                    addBtn.style.display = 'inline-block';
                }
            });

            increaseBtns[index].addEventListener('click', () => {
                quantity++;
                quantityDisplays[index].textContent = quantity;
            });
        });
    </script>

        <!-- Navbar Bawah -->
        <div class="bottom-navbar">
        <div class="nav-item">
            <button class="nav-button" onclick="alert('Menu clicked!')">
                <img src="menu.png" alt="Menu" class="nav-icon">
                <p class="nav-text">MENU</p>
            </button>
        </div>
        <div class="nav-item">
            <button class="nav-button" onclick="alert('Keranjang clicked!')">
                <img src="keranjang.png" alt="Keranjang" class="nav-icon">
                <p class="nav-text">KERANJANG</p>
            </button>
        </div>
    </div>

</body>
</html>
