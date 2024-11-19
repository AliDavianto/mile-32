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

    public function pembayaran(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|integer',
            'metode_pembayaran' => 'required|in:Digital,Non-Digital',
            'total_pembayaran' => 'required|integer',
            'status_pembayaran' => 'required|in:berhasil,gagal,menunggu',
            'waktu_transaksi' => 'required|date',
            'produk' => 'required|array',
            'produk.*.harga' => 'required|integer',
            'produk.*.jumlah' => 'required|integer',
            'produk.*.nama_produk' => 'required|string',
            
        ]);

        $pembayaran = Pembayaran::create($request->all());

        if ($request->metode_pembayaran == 'Digital') {

            $itemDetails = array_map(function ($produk) {
                return [
                    'price' => $produk['harga'],
                    'quantity' => $produk['jumlah'],
                    'name' => $produk['nama_produk'],
                ];
            }, $request->produk);
            
            $params = [
                'transaction_details' => [
                    'order_id' => $pembayaran->id,
                    'gross_amount' => $request->total_pembayaran,
                ],

                'item_details' => $itemDetails,

                'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'bri_va']
            ];

            $auth = base64_encode(env('MIDTRANS_SERVER_KEY') . ':'); // Append ':' for the correct format
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth"
            ];

            // Make the API call with SSL verification disabled
             $response = Http::withHeaders($headers)
            ->withOptions(['verify' => false]) // Disable SSL verification for testing
            ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            // Decode the response from Midtrans
             $response = json_decode($response->body());

        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil ditambahkan.',
            
        ], 201);
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
                            'produk' => $detail->menu->nama_produk,
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
