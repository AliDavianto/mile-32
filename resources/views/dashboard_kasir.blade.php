<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="dashboardkasir.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/dashboard_kasir.js') }}"></script>

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

    <!-- Cards for Pesanan -->
    @foreach ($pesananData as $pesanan)
    <div class="card" data-id-pesanan="{{ $pesanan['id_pesanan'] }}">
        <div class="meja">Meja {{ $pesanan['nomor_meja'] }}</div> <!-- Meja label -->
    
        @foreach ($pesanan['pesanan'] as $item)
        <div class="item">
            <span>{{ $item['nama_produk'] }} ({{ $item['kuantitas'] }}x)</span>
            <span>Rp{{ number_format($item['harga'] * $item['kuantitas'], 0, ',', '.') }}</span>
        </div>
        @endforeach
    
        <div class="item total">
            <span>Total Harga</span>
            <span>Rp{{ number_format($pesanan['total_harga'], 0, ',', '.') }}</span>
        </div>
        <div class="buttons">
            <div class="btn btn-batal">Batal</div>
            <div class="btn btn-konfirmasi">Konfirmasi</div>
            
        </div>
    </div>
    @endforeach
</body>

</html>
