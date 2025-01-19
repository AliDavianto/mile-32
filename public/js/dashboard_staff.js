document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Attach click event listener to all "Selesai" buttons
    document.querySelectorAll('.btn-selesai').forEach(button => {
        button.addEventListener('click', async () => {
            const idPesanan = button.getAttribute('data-id');

            try {
                const response = await fetch('/dashboardstaff', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ id_pesanan: idPesanan }),
                });

                if (response.ok) {
                    // Handle success (e.g., remove the card or show a success message)
                    alert('Pesanan selesai!');
                    button.closest('.card').remove();
                } else {
                    // Handle server-side errors
                    const errorData = await response.json();
                    alert(`Error: ${errorData.message}`);
                }
            } catch (error) {
                // Handle network errors
                console.error('Request failed:', error);
                alert('Terjadi kesalahan, silakan coba lagi.');
            }
        });
    });
});
