<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kategori - Mile 32</title>
    <link rel="stylesheet" href="kategori.css">
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

    <!-- Form Section -->
    <div class="form-container">
        <img src="loginlogo.jpg" alt="Illustration">
        <h2>DAFTARKAN KATEGORI BARU</h2>
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="email">KATEGORI</label>
            <input type="text" id="email" name="email" placeholder="Masukan Kategori">
            
            <button type="submit" class="update-btn">Kirim</button>
        </form>
        
    </div>
</body>

</html>
