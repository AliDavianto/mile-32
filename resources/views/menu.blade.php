<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu Mile Cafe 32</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo-container">
            <img src="logo mile.png" alt="Logo Mile Cafe" class="logo-image">
            <div class="logo-text">
                <h1>Mile 32</h1>
                <p>Temukan harmoni rasa dan cerita di Miles 32, tempat membawa pengalaman baru!</p>
            </div>
        </div>
    </header>

    <!-- Menu Items -->
    <main class="menu-section">
        @foreach ($menus as $menu)
            <div class="menu-box">
                <div class="menu-image">
                    <img src="miegoreng.png" alt="gambar">
                </div>
                <div class="menu-description">
                    <p class="title">{{ $menu['nama_produk'] }}</p>
                    <p class="description">{{ $menu['deskripsi'] }}</p>
                    <div class="price-controls">
                        <p class="price">Rp {{ number_format($menu['harga'], 0, ',', '.') }}</p>
                        <!-- Set quantity controls to be always displayed with a default value of 0 -->
                        <div class="quantity-controls" style="display: flex;">
                            <button class="decrease-btn" disabled>−</button>
                            <span class="quantity">0</span>
                            <button class="increase-btn">+</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </main>

    <!-- Clear Local Storage Button -->
    <button id="clear-storage-btn">Clear Local Storage</button>

    <!-- Bottom Navbar -->
    <footer class="bottom-navbar">
        <div class="nav-item">
            <button class="nav-button" onclick="location.href='/menu'">
                <img src="menu.png" alt="Menu" class="nav-icon">
                <p class="nav-text">MENU</p>
            </button>
        </div>
        <div class="nav-item">
            <button class="nav-button" onclick="location.href='/cart'">
                <img src="keranjang.png" alt="Keranjang" class="nav-icon">
                <p class="nav-text">KERANJANG</p>
            </button>
        </div>
    </footer>

    <!-- Include JSON Data in Blade Template -->
    <script>
        const menus = @json($menus);
    </script>

    <!-- External JavaScript -->
    <script src="{{ asset('js/menu.js') }}"></script>
</body>

</html>
