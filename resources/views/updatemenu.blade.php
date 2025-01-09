<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Menu - Mile 32</title>
    <link rel="stylesheet" href="../../updatejabatan.css">
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
        <h2>UPDATE Menu</h2>
        
        <form action="{{ route('changemenu', $menu->id_menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')  <!-- Ensure it's treated as an update request -->
            
            <!-- NAMA PRODUK -->
            <label for="nama_produk">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $menu->nama_produk) }}" required>
            @error('nama_produk')
                <span class="error-message">{{ $message }}</span>
            @enderror
            
            <!-- DESKRIPSI -->
<label for="deskripsi">Deskripsi</label>
<textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $menu->deskripsi) }}</textarea>
@error('deskripsi')
    <span class="error-message">{{ $message }}</span>
@enderror

            
            <!-- HARGA -->
<label for="harga">Harga</label>
<input type="text" id="harga" name="harga" 
       value="{{ old('harga', $menu->harga) }}" required>
@error('harga')
    <span class="error-message">{{ $message }}</span>
@enderror

<!-- KATEGORI -->
<label for="id_kategori">Kategori</label>
<select id="id_kategori" name="id_kategori" required>
    <option value="">Pilih Kategori</option>
    @foreach ($categories as $kategori)
        <option value="{{ $kategori->id_kategori }}" 
            {{ $menu->id_kategori == $kategori->id_kategori ? 'selected' : '' }}>
            {{ $kategori->kategori }}
        </option>
    @endforeach
</select>
@error('id_kategori')
    <span class="error-message">{{ $message }}</span>
@enderror

<!-- GAMBAR (Current Image Preview) -->
<label for="gambar">Gambar</label>
@if($menu->gambar)
    <div>
        <img src="{{ asset($menu->gambar) }}" alt="Current Image">
    </div>
@endif
<input type="file" id="gambar" name="gambar" accept="image/*">
<small>Pilih gambar baru jika ingin mengganti (format: .jpg, .jpeg, .png).</small>
@error('gambar')
    <span class="error-message">{{ $message }}</span>
@enderror

            
            <br>
            <button type="submit" class="update-btn">Update</button>
        </form>
        
        
    </div>
</body>

</html>
