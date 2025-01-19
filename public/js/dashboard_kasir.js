document.addEventListener('DOMContentLoaded', function () {
    // Handle confirmation and cancellation
    document.querySelectorAll('.buttons .btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const card = button.closest('.card');
            const idPesanan = card.getAttribute('data-id-pesanan');
            const isConfirmation = button.classList.contains('btn-konfirmasi');
            const status = isConfirmation ? 1 : 4; // Status 1 for "Konfirmasi", 4 for "Batal"

            const action = isConfirmation
                ? `mengonfirmasi pesanan ${idPesanan}`
                : `membatalkan pesanan ${idPesanan}`;

            if (confirm(`Apakah Anda yakin ingin ${action}?`)) {
                fetch('/dashboardkasir', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        id_pesanan: idPesanan,
                        status: status,
                    }),
                })
                    .then((response) => {
                        if (response.ok) {
                            alert(`Pesanan berhasil ${isConfirmation ? 'dikonfirmasi' : 'dibatalkan'}!`);
                            window.location.reload();
                        } else {
                            alert(`Gagal ${isConfirmation ? 'mengonfirmasi' : 'membatalkan'} pesanan. Silakan coba lagi.`);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            }
        });
    });
});
