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
        <h2>UPDATE Status</h2>
        
        <form action="{{ route('changestatus', $status->id_status) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')  <!-- Ensure it's treated as an update request -->
            
            <!-- NAMA PRODUK -->
            <label for="status">Nama Produk</label>
            <input type="text" id="status" name="status" 
                   value="{{ old('status', $status->status) }}" required>
            @error('status')
                <span class="error-message">{{ $message }}</span>
            @enderror
            
            
            <br>
            <button type="submit" class="update-btn">Update</button>
        </form>
        
        
    </div>
</body>

</html>
