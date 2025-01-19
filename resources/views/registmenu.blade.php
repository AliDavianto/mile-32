<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Menu - Mile 32</title>
    <link rel="stylesheet" href="../registmenu.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo-container">
            <img src="../logo mile.png" alt="Logo Mile Cafe" class="logo">
            <div class="logo-text">
                <h1>Mile 32</h1>
                <p>Temukan harmoni rasa dan cerita di Miles 32, tempat membawa pengalaman baru!</p>
            </div>
        </div>
        <!-- Menu lainnya dapat ditambahkan di sini -->
    </header>

    <!-- Form Section -->
    <div class="form-container">
        <img src="../loginlogo.jpg" alt="Illustration" class="login-logo">
        <h2 class="title">WELCOME TO MILE 32</h2>
        <form action="{{ url('/menu/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
        
            <label for="nama_produk">NAMA PRODUK</label>
            <input type="text" id="nama_produk" name="nama_produk" placeholder="Masukkan Nama Produk" required>
            @error('nama_produk')
                <span class="error-message">{{ $message }}</span>
            @enderror
        
            <label for="deskripsi">DESKRIPSI</label>
            <input type="text" id="deskripsi" name="deskripsi" placeholder="Masukkan Deskripsi">
            @error('deskripsi')
                <span class="error-message">{{ $message }}</span>
            @enderror
        
            <label for="harga">HARGA PRODUK</label>
            <input type="text" id="harga" name="harga" placeholder="Masukkan Harga Produk" required>
            @error('harga')
                <span class="error-message">{{ $message }}</span>
            @enderror
        
            <label for="id_kategori">KATEGORI</label>
            <select id="id_kategori" name="id_kategori" required>
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $kategori)
                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->kategori }}</option>
                @endforeach
            </select>
            @error('id_kategori')
                <span class="error-message">{{ $message }}</span>
            @enderror
        
            <label for="gambar">UPLOAD GAMBAR</label>
            <input type="file" id="gambar" name="gambar" accept="image/*" required>
            <small>Pilih gambar dengan format .jpg, .jpeg, atau .png.</small>
            @error('gambar')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <br>
        
            <button type="submit" class="submit-btn">Kirim</button>
        </form>
        
    </div>

</body>

</html>
