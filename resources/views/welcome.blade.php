<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .menu-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .menu-item {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1>Menu</h1>
        @if($menu && count($menu) > 0)
            @foreach($menu as $item)
                <div class="menu-item">
                    <strong>{{ $item['nama_produk'] }}</strong><br>
                    <small>{{ $item['deskripsi'] }}</small><br>
                    <span>Harga: Rp{{ number_format($item['harga'], 0, ',', '.') }}</span>
                </div>
            @endforeach
        @else
            <p class="menu-item">Menu tidak tersedia.</p>
        @endif
    </div>
</body>
</html>
