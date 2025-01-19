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
            <div class="payment-container">
                <h2 class="payment-title">Detail Pembayaran</h2>
                <div class="payment-details">
                    <div class="payment-row">
                        <span>Total</span>
                        <span id="total-amount">Rp0</span>
                    </div>
                    <div class="payment-row">
                        <label for="payment-method">Metode Pembayaran</label>
                        <div class="select-container">
                            <select id="payment-method" class="styled-dropdown">
                                <option selected disabled value=""> Pilih metode </option>
                                <option value="qris">QRIS</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                    </div>
                    <form id="checkout-form" onsubmit="event.preventDefault(); handleCheckout();">
                        <input type="hidden" id="pesanan-input" name="pesanan">
                        <button type="submit" class="checkout-button">CHECKOUT</button>
                    </form>
                
                
                <form action="{{ route('pembayaran') }}" method="POST">
                @csrf

                <div class="bayar-section">
                    <button id="bayar-btn" class="checkout-button">Bayar</button>
                </div>

                
            </form>
            </div>
</main>


    <!-- Clear Local Storage Button -->
    {{-- <button id="clear-storage-btn">Clear Local Storage</button> --}}

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
