<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Menu</title>
    <link rel="stylesheet" href="adminmenu.css">
    <!-- Menambahkan Font Awesome CDN untuk ikon kaca pembesar -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="#laporan"><i class="icon">📄</i> Laporan Keuangan</a></li>
            <li class="active"><a href="#menu"><i class="icon">📋</i> Menu</a></li>
            <li><a href="#staff"><i class="icon">👥</i> Staff</a></li>
            <li><a href="#kategori"><i class="icon">📋</i> Kategori</a></li>
            <li><a href="#jabatan"><i class="icon">📋</i> Jabatan</a></li>
            <li><a href="#status"><i class="icon">📋</i> Status</a></li>
        </ul>
        <button class="logout">Logout</button>
    </div>

    <div class="main-content">
        <div class="header">
            <h3>Data Menu</h3>
            <div class="header-actions">
                <!-- Menambahkan ikon kaca pembesar di sini -->
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search" class="search-box">
                <!-- Tombol "Tambah" mengarah ke route pendaftaran -->
                <a href="{{ url('/pendaftaran') }}">
                    <button class="add-button">Tambah</button>
                </a>
            </div>
        </div>
        
        <div class="table-container">
            <table>
            <thead>
    <tr>
        <th>Nama</th>
        <th>Deskripsi</th>
        <th>Harga</th>
        <th>Kategori</th>
        <th>Diskon</th>
        <th>Gambar</th>
        <th></th> <!-- Header hanya menjelaskan tujuan kolom -->
    </tr>
</thead>
<tbody>
    @for ($i = 0; $i < 8; $i++)
        <tr>
            <td>Jane Cooper</td>
            <td>{{ $i < 2 ? 'Kasir' : 'Staff Dapur' }}</td>
            <td>(225) 555-0118</td>
            <td>Kategori 1</td>
            <td>10%</td>
            <td><img src="example.jpg" alt="Gambar Produk" style="width: 100px; height: auto;"></td>
            <td> <!-- Tombol dalam isi tabel -->
                <a href="{{ url('/registmenu') }}">
                    <button class="edit-button">Edit</button>
                </a>
                <button class="delete-button">Hapus</button>
            </td>
        </tr>
    @endfor
</tbody>

            </table>
            <div class="pagination">
                <span>Showing data 1 to 8 of 256K entries</span>
                <div class="page-numbers">
                    <button class="page-arrow">&larr;</button> <!-- Left Arrow (Previous) -->
                    <button>1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>4</button>
                    <button>...</button>
                    <button>40</button>
                    <button class="page-arrow">&rarr;</button> <!-- Right Arrow (Next) -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
