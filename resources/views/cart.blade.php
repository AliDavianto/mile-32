<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Mile Cafe 32</title>
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

    <!-- Cart Items -->
    <main class="menu-section">
        <h1>Your Cart</h1>
        <div id="cart-items" class="cart-items-container">
            <!-- Cart items will be populated here by JavaScript -->
        </div>
        <div id="total-price" class="total-price">
            <!-- Total price will be populated here by JavaScript -->
        </div>

        <button id="checkout-btn" class="checkout-button">CHECKOUT</button>

        <!-- Payment Method Section -->
        <section class="">
            <h2>Metode Pembayaran</h2>
            <label for="payment-method">Pilih Metode:</label>
            <select id="payment-method" class="payment-method-select">
                <option value="digital">Digital</option>
                <option value="non-digital">Non Digital</option>
            </select>
        </section>

        <form action="{{ route('pembayaran') }}" method="POST">
            @csrf
            <button type="submit">Bayar</button>
        </form>


    </main>

    <!-- Clear Local Storage Button -->
    <button id="clear-storage-btn">Clear Local Storage</button>

    <!-- Bottom Navbar -->
    <footer class="bottom-navbar">
        <div class="nav-item">
            <button class="nav-button" onclick="location.href='/menu'">
                <img src="menuputih.png" alt="Menu" class="nav-icon">
                <p class="nav-text">MENU</p>
            </button>
        </div>
        <div class="nav-item">
            <button class="nav-button" onclick="location.href='/cart'">
                <img src="keranjanghitam.png" alt="Keranjang" class="nav-icon">
                <p class="nav-text">KERANJANG</p>
            </button>
        </div>
    </footer>

    <!-- Include JSON Data in Blade Template -->
    <script>
        const menus = @json($menus);
    </script>

    <!-- External JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>

    <script>
        // Update the hidden input with the orderState before form submission
        document.getElementById('checkout-form').addEventListener('submit', (event) => {
            const pesananInput = document.getElementById('pesanan-input');
            // Convert the localStorage data to JSON and set it in the hidden input
            pesananInput.value = JSON.stringify(JSON.parse(localStorage.getItem('pesanan')) || []);
        });
    </script>
</body>

</html>
