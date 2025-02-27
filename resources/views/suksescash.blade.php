<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Sukses</title>
    <link rel="stylesheet" href="sukses.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo-container">
            <img src="logo mile.png" alt="Logo Mile Cafe" class="logo-image">
            <div class="logo-text">
                <h1>Miles 32</h1>
                <p>Temukan harmoni rasa dan cerita di Miles 32, tempat membawa pengalaman baru!</p>
            </div>
        </div>
    </header>

    <!-- Gambar di bawah navbar -->
    <div class="image-container">
        <img src="berhasil.png" alt="Gambar Berhasil" class="success-image">

        <!-- Teks di bawah gambar -->
        <div class="text-container">
        <h2 class="payment-success">Nomor Meja Anda: {{ $meja }}</h2>
            <h3 class="enjoy-message">“Silahkan sebutkan nomor meja dan bayar ke kasir”</h3>

            <!-- Teks Back To Home -->
            <a href="/menu" class="back-to-home">Back To Menu</a>

        </div>
    </div>

</body>

</html>
