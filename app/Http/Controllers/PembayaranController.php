<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;

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
}
