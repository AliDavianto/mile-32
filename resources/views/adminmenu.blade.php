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
            <li class="{{ request()->is('adminlaporan') ? 'active' : '' }}">
                <a href="{{ url('/adminlaporan') }}"><i class="icon">📄</i> Laporan Keuangan</a>
            </li>
            <li class="{{ request()->is('adminmenu') ? 'active' : '' }}">
                <a href="{{ url('/adminmenu') }}"><i class="icon">📋</i> Menu</a>
            </li>
            <li class="{{ request()->is('adminstaff') ? 'active' : '' }}">
                <a href="{{ url('/adminstaff') }}"><i class="icon">👥</i> Staff</a>
            </li>
            <li class="{{ request()->is('adminkategori') ? 'active' : '' }}">
                <a href="{{ url('/adminkategori') }}"><i class="icon">📋</i> Kategori</a>
            </li>
            <li class="{{ request()->is('adminjabatan') ? 'active' : '' }}">
                <a href="{{ url('/adminjabatan') }}"><i class="icon">📋</i> Jabatan</a>
            </li>
            <li class="{{ request()->is('adminstatus') ? 'active' : '' }}">
                <a href="{{ url('/adminstatus') }}"><i class="icon">📋</i> Status</a>
            </li>
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
                <a href="{{ url('/menu/create') }}">
                    <button class="add-button">Tambah</button>
                </a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Menu</th>
                        <th>Nama Produk</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                        <th></th> <!-- Header hanya menjelaskan tujuan kolom -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr>
                            <td>{{ $menu->id_menu }}</td>
                            <td>{{ $menu->nama_produk }}</td>
                            <td>{{ $menu->deskripsi }}</td>
                            <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td>{{ $menu->kategori ? $menu->kategori->kategori : 'Tidak Ada Kategori' }}</td>
                            <td>
                                <img src="{{ asset($menu->gambar) }}" alt="{{ $menu->nama_produk }}"
                                    style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <a href="{{ route('editmenu', ['id' => $menu->id_menu]) }}">
                                    <button class="edit-button">Edit</button>
                                </a>                                
                                <form action="{{ route('destroymenu', ['id' => $menu->id_menu]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
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
