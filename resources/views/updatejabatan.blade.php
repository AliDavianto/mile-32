<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Jabatan - Mile 32</title>
    <link rel="stylesheet" href="../../registmenu.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo-container">
            <img src="../../logo mile.png" alt="Logo Mile Cafe" class="logo">
            <div class="logo-text">
                <h1>Mile 32</h1>
                <p>Temukan harmoni rasa dan cerita di Miles 32, tempat membawa pengalaman baru!</p>
            </div>
        </div>
        <!-- Menu lainnya dapat ditambahkan di sini -->
    </header>

    <!-- Form Section -->
    <div class="form-container">
        <img src="../../loginlogo.jpg" alt="Illustration" class="login-logo">
        <h2 class="title">WELCOME TO MILE 32</h2>
        <form action="{{ route('changejabatan', $jabatan->id_jabatan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST') <!-- Ensure it's treated as an update request -->

            <!-- NAMA PRODUK -->
            <label for="jabatan">Jabatan</label>
            <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $jabatan->jabatan) }}" required>
            @error('jabatan')
                <span class="error-message">{{ $message }}</span>
            @enderror


            <br>
            <button type="submit" class="submit-btn">Update</button>
        </form>

    </div>

</body>

</html>
