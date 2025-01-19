<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Mile Cafe 32</title>
    <link rel="stylesheet" href="dashboardstaff.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/dashboard_staff.js') }}"></script>
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

    <!-- Cards Section -->
    @foreach ($pesananData as $pesanan)
        <div class="card">
            <!-- Meja Label -->
            <div class="meja">Meja {{ $pesanan['nomor_meja'] }}</div>

            <!-- Items -->
            @foreach ($pesanan['pesanan'] as $item)
                <div class="item">
                    <span>{{ $item['nama_produk'] }} ({{ $item['kuantitas'] }}x)</span>
                </div>
            @endforeach

            <!-- Button -->
            <div class="buttons">
                <div class="btn-selesai" data-id="{{ $pesanan['id_pesanan'] }}">Selesai</div>
            </div>
        </div>
    @endforeach

    
</body>

</html>
