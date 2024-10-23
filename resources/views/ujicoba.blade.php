<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mile Cafe 32</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
</head>
<body>
    <div class="menu-box">
        <div class="menu-image">
            <img src="miegoreng.png" alt="Mie Goreng Jawa">
        </div>
        <div class="menu-description">
            <p class="title">Mie Goreng Jawa</p>
            <p class="description">Mie Goreng khas Jawa dengan kecap manis, sayuran, dan ayam, memberikan rasa gurih manis.</p>
            <div class="price-controls">
                <p class="price">Rp 18.000</p>

                <!-- Tombol Add dan Kontrol Jumlah -->
                <button id="add-btn" class="add-btn">Add</button>
                <div class="quantity-controls" id="quantity-controls" style="display: none;">
                    <button id="decrease-btn">−</button>
                    <span id="quantity">1</span>
                    <button id="increase-btn">+</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk menangani perubahan jumlah -->
    <script>
        const decreaseBtn = document.getElementById('decrease-btn');
        const increaseBtn = document.getElementById('increase-btn');
        const quantityDisplay = document.getElementById('quantity');
        const addBtn = document.getElementById('add-btn');
        const quantityControls = document.getElementById('quantity-controls');
        let quantity = 1;

        // Saat tombol Add diklik, sembunyikan tombol Add dan tampilkan kontrol jumlah
        addBtn.addEventListener('click', () => {
            addBtn.style.display = 'none';
            quantityControls.style.display = 'flex';
        });

        decreaseBtn.addEventListener('click', () => {
            if (quantity > 1) {
                quantity--;
                quantityDisplay.textContent = quantity;
            } else if (quantity === 1) {
                // Jika kuantitas sama dengan 1 dan tombol - ditekan, kembalikan tombol Add
                quantityControls.style.display = 'none';
                addBtn.style.display = 'inline-block';
            }
        });

        increaseBtn.addEventListener('click', () => {
            quantity++;
            quantityDisplay.textContent = quantity;
        });
    </script>
</body>
</html>
