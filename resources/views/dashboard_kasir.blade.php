<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Mile Cafe 32</title>
    <link rel="stylesheet" href="dashboardkasir.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
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

    <!-- Card Pesanan -->
    <div class="card">
        <div class="meja">Meja 1</div> <!-- Meja label -->

        <div class="item">
            <span>Mie Goreng Jawa (1x)</span>
            <span>Rp18.000</span>
        </div>
        <div class="item">
            <span>Nasi Goreng Ayam (1x)</span>
            <span>Rp17.000</span>
        </div>
        <div class="item">
            <span>Jasmine Tea (2x)</span>
            <span>Rp16.000</span>
        </div>
        <div class="item total">
            <span>Total Harga</span>
            <span>Rp51.000</span>
        </div>
        <div class="buttons">
            <div class="btn btn-batal">Batal</div>
            <div class="btn btn-konfirmasi">Konfirmasi</div>
        </div>
    </div>
</body>

</html>
