<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Jabatan - Mile 32</title>
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
        <h2>UPDATE JABATAN</h2>
        
        <form action="{{ route('changejabatan', $jabatan->id_jabatan) }}" method="POST">
            @csrf
            <label for="jabatan">Jabatan</label>
            <input type="text" id="jabatan" name="jabatan" placeholder="Update Kategori" value="{{ $jabatan->jabatan }}" required>
            <button type="submit" class="update-btn">Update</button>
        </form>
        
    </div>
</body>

</html>
