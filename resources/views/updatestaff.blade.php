<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Staff - Mile 32</title>
    <link rel="stylesheet" href="../../updatestaff.css">
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
        <h2>UPDATE STAFF</h2>

        <form action="{{ route('changestaff', ['id' => $staff->id_user]) }}" method="POST">
            @csrf

            <label for="nama">NAMA STAFF</label>
            <input type="text" id="nama" name="nama" value="{{$staff->nama}}" placeholder="Masukkan Nama" required>
            @error('nama')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <label for="email">EMAIL</label>
            <input type="text" id="email" name="email" value="{{$staff->email}}"placeholder="Masukkan Email" required>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <label for="password">PASSWORD</label>
            <input type="password" id="password" name="password" placeholder="Masukkan Password" 
                minlength="8">
            @error('password')  
                <span class="error-message">{{ $message }}</span>
            @enderror

            <label for="password_confirmation">KONFIRMASI PASSWORD</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Konfirmasi Password"  minlength="8">
            @error('password_confirmation')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <label for="jabatan">JABATAN</label>
            <select name="jabatan" id="jabatan" required>
                <!-- Placeholder displaying the current jabatan -->
                <option value="" disabled selected>{{ $staff->jabatan->jabatan ?? 'Pilih Jabatan' }}</option>
            
                <!-- Loop through available jabatan options -->
                @foreach ($jabatans as $jabatan)
                    <option value="{{ $jabatan->id_jabatan }}" 
                        {{ $staff->jabatan->id_jabatan == $jabatan->id_jabatan ? 'selected' : '' }}>
                        {{ $jabatan->jabatan }}
                    </option>
                @endforeach
            </select>
            @error('jabatan')
                <span class="error-message">{{ $message }}</span>
            @enderror

            <button type="submit" class="update-btn">Update</button>
        </form>

    </div>
</body>

</html>
