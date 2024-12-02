<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::all();
        return view('pembayarans.index', compact('pembayarans'));
    }
    public function create()
    {
        return view('pembayarans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|integer',
            'metode_pembayaran' => 'required|in:Digital,Non-Digital',
            'total_pembayaran' => 'required|integer',
            'status_pembayaran' => 'required|in:berhasil,gagal,menunggu',
            'waktu_transaksi' => 'required|date',
        ]);

        $pembayaran = Pembayaran::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil ditambahkan.',
            'data' => $pembayaran
        ], 201);
    }


    public function pembayaran(Request $id_pesanan)
    {
        // Query the Pesanan model with related data
        // Find the latest Pesanan record
        $pesanan = Pesanan::with(['pembayaran', 'detailPesanan'])
            ->latest('id_pesanan') // Order by `id_pesanan` descending
            ->firstOrFail();       // Get the latest Pesanan or fail if not found
        $idPesananLatest = Pesanan::latest('id_pesanan')->value('id_pesanan');

        // Transform the data into the desired format
        $data = [
            'id_pesanan' =>  $idPesananLatest,  // Placeholder, you can use $pesanan->id_pesanan for real data
            'metode_pembayaran' => $pesanan->pembayaran->metode_pembayaran ?? null,
            'total_pembayaran' => $pesanan->pembayaran->total_pembayaran ?? null,
            'status_pembayaran' => $pesanan->pembayaran->status_pembayaran ?? null,
            'waktu_transaksi' => $pesanan->pembayaran->waktu_transaksi ?? null,
            'produk' => $pesanan->detailPesanan->map(function ($detail) {
                return [
                    'harga' => $detail->harga,
                    'jumlah' => $detail->kuantitas,
                    'id_menu' => $detail->id_menu,
                ];
            })->toArray(),
        ];

        // Transform item details for Midtrans API
        $itemDetails = array_map(function ($item) {
            return [
                'price' => $item['harga'],
                'quantity' => $item['jumlah'],
                'name' => $item['id_menu']
            ];
        }, $data['produk']);

        // Build Midtrans payload
        $midtransPayload = [
            'transaction_details' => [
                'order_id' => $data['id_pesanan'],
                'gross_amount' => $data['total_pembayaran'],
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => ['gopay'] // Enable QRIS
        ];

        // Midtrans server key
        $serverKey = "SB-Mid-server-5eCkCLlExn9bXjINkjCGKCNj";
        if (!$serverKey) {
            return response()->json(['success' => false, 'message' => 'Server Key is not configured.'], 500);
        }

        $auth = base64_encode($serverKey . ':');
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth"
        ];

        try {
            // Send the request to Midtrans
            $response = Http::withHeaders($headers)
                ->withOptions(['verify' => false]) // Disable SSL only for sandbox/testing
                ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $midtransPayload);

            $responseBody = json_decode($response->body(), true);

            if ($response->successful()) {
                // Check if the response contains the QRIS URL
                $redirectUrl = $responseBody['redirect_url'] ?? null;

                if ($redirectUrl) {
                    // Clean the URL by removing backslashes
                    $cleanRedirectUrl = str_replace('\\', '', $redirectUrl);

                    // Redirect to the clean QRIS URL
                    return redirect()->away($cleanRedirectUrl);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'QRIS payment URL not found.',
                        'error' => $responseBody
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran gagal diproses.',
                    'error' => $responseBody
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'id_pesanan' => 'required|integer',
            'metode_pembayaran' => 'required|in:Digital,Non-Digital',
            'total_pembayaran' => 'required|integer',
            'status_pembayaran' => 'required|in:berhasil,gagal,menunggu',
            'waktu_transaksi' => 'required|date',
        ]);

        $pembayaran->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diperbarui.',
            'data' => $pembayaran
        ], 200);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dihapus.'
        ], 200);
    }
    public function getPesananNonDigital()
    {
        // Langkah 1: Ambil semua id_pesanan di tabel Pembayarans dengan metode_pembayaran = "non-digital" dan status_pembayaran = "menunggu"
        $listIdPesananNonDigital = Pembayaran::where('metode_pembayaran', 'non-digital')
            ->where('status_pembayaran', 'menunggu')
            ->pluck('id_pesanan')
            ->toArray();

        // Langkah 2: Ambil data pesanan dan detail pesanan yang sesuai dengan listIdPesananNonDigital
        $pesananData = Pesanan::with(['detailPesanan.menu'])
            ->whereIn('id_pesanan', $listIdPesananNonDigital)
            ->get()
            ->map(function ($pesanan) {
                return [
                    'id_pesanan' => $pesanan->id_pesanan,
                    'nomor_meja' => $pesanan->nomor_meja,
                    'total_harga' => $pesanan->total_pembayaran,
                    'pesanan' => $pesanan->detailPesanan->map(function ($detail) {
                        return [
                            'produk' => $detail->menu->id_menu,
                            'kuantitas' => $detail->kuantitas,
                            'harga' => $detail->harga,
                        ];
                    }),
                ];
            });

        // Langkah 3: Kembalikan data dalam bentuk JSON
        return response()->json($pesananData);
    }

    // Method untuk konfirmasi pesanan
    public function konfirmasiPesanan(Request $request, $id_pesanan)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:berhasil,gagal',
            'status_pesanan' => 'required|in:berhasil,gagal,menunggu',
        ]);

        // Cari pesanan berdasarkan id_pesanan
        $pesanan = Pesanan::findOrFail($id_pesanan);

        // Perbarui status pembayaran dan pesanan
        $pesanan->pembayaran->update([
            'status_pembayaran' => $request->status_pembayaran,
        ]);
        $pesanan->update([
            'status_pesanan' => $request->status_pesanan,
        ]);

        return response()->json([
            'message' => 'Status pembayaran dan pesanan berhasil diperbarui',
            'status_pembayaran' => $pesanan->pembayaran->status_pembayaran,
            'status_pesanan' => $pesanan->status_pesanan,
        ]);
    }

    public function batalPesanan(Request $request, $id_pesanan)
    {
        try {
            // Cari pesanan berdasarkan id_pesanan
            $pesanan = Pesanan::findOrFail($id_pesanan);

            // Periksa apakah pesanan memiliki pembayaran terkait
            $pembayaran = $pesanan->pembayaran;

            if (!$pembayaran) {
                return response()->json([
                    'message' => 'Pembayaran terkait tidak ditemukan untuk pesanan ini.'
                ], 404);
            }

            // Perbarui status pembayaran dan status pesanan
            $pembayaran->update([
                'status_pembayaran' => 'gagal', // Atau status lain yang menunjukkan pembatalan
            ]);
            $pesanan->update([
                'status_pesanan' => 'gagal', // Atau status lain yang sesuai
            ]);

            return response()->json([
                'message' => 'Pesanan berhasil dibatalkan.',
                'id_pesanan' => $pesanan->id_pesanan,
                'status_pesanan' => $pesanan->status_pesanan,
                'status_pembayaran' => $pembayaran->status_pembayaran,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat membatalkan pesanan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
