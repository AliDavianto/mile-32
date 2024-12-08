<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran - Mile 32</title>
    <link rel="stylesheet" href="../../login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo-container">
            <img src="../../logo mile.png" alt="Logo Mile Cafe" class="logo-image">
            <div class="logo-text">
                <h1>Miles 32</h1>
                <p>Temukan harmoni rasa dan cerita di Miles 32, tempat membawa pengalaman baru!</p>
            </div>
        </div>
    </header>

    <!-- Form Section -->
    <div class="form-container">
        <img src="../../loginlogo.jpg" alt="Illustration">
        <h2>DAFTARKAN STAFF BARU</h2>

        <form action="{{ route('storestaff') }}" method="POST">
            @csrf

            <label for="nama">NAMA STAFF</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan Nama" required>
            @error('nama')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <label for="email">EMAIL</label>
            <input type="text" id="email" name="email" placeholder="Masukkan Email" required>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <label for="password">PASSWORD</label>
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required
                minlength="8">
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <label for="password_confirmation">KONFIRMASI PASSWORD</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Konfirmasi Password" required minlength="8">
            @error('password_confirmation')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <label for="jabatan">JABATAN</label>
            <select name="id_jabatan" id="jabatan" required>
                <option value="">Pilih Jabatan</option>
                @foreach ($jabatans as $jabatan)
                    <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->jabatan }}</option>
                @endforeach
            </select>
            @error('id_jabatan')
                <span class="error-message">{{ $message }}</span>
            @enderror
            @error('jabatan')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <button type="submit" class="update-btn">Daftar</button>
        </form>

    </div>
</body>

</html>
