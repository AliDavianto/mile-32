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

    <!-- Search Bar -->

    <!-- Menu Items -->
    <main class="menu-section">
    <section class="search-bar">
        <div class="search-container">
            <input type="text" id="search-input" class="search-input" placeholder="Cari menu..." />
        </div>
    </section>
        <div id="kategori-1" class="kategori-section">
            <h2>Makanan</h2>
            <div class="menu-container"></div>
        </div>
        <div id="kategori-2" class="kategori-section">
            <h2>Minuman</h2>
            <div class="menu-container"></div>
        </div>
        <div id="kategori-4" class="kategori-section">
            <h2>Snack</h2>
            <div class="menu-container"></div>
        </div>
    </main>

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
    <script src="{{ asset('js/menu.js') }}?v={{ time() }}"></script>
</body>

</html>
