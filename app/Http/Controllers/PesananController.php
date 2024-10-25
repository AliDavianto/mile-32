<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PesananController extends Controller
{
    // Method untuk entry ke tabel pesanan
    public function createPesanan(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'nomor_meja' => 'required|integer',
            'pesanan' => 'required|array',
            'pesanan.*.id_menu' => 'required|integer',
            'pesanan.*.kuantitas' => 'required|integer|min:1',
            'pesanan.*.harga' => 'required|numeric|min:0',
        ]);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Simpan data ke tabel Pesanan
            $pesanan = Pesanan::create([
                'nomor_meja' => $validatedData['nomor_meja'],
                'waktu_pemesanan' => now(),
                'status_pesanan' => 'pending', // atau status yang diinginkan
                'total_pembayaran' => array_reduce($validatedData['pesanan'], function ($total, $item) {
                    return $total + ($item['kuantitas'] * $item['harga']);
                }, 0),
            ]);

            // Dapatkan id_pesanan dari pesanan yang baru disimpan
            $id_pesanan = $pesanan->id_pesanan;

            // Panggil method baru untuk menyimpan detail pesanan
            $this->createDetailPesanan($validatedData['pesanan'], $id_pesanan);

            // Commit transaksi jika semua berhasil
            DB::commit();

            // Return response sukses
            return response()->json([
                'message' => 'Pesanan berhasil disimpan',
                'data' => $pesanan,
            ], 201);

        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Return response error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan pesanan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Method untuk menyimpan detail pesanan
    protected function createDetailPesanan(array $pesanan, int $id_pesanan)
    {
        foreach ($pesanan as $item) {
            DetailPesanan::create([
                'id_pesanan' => $id_pesanan,
                'id_menu' => $item['id_menu'],
                'kuantitas' => $item['kuantitas'],
                'harga' => $item['harga'],
            ]);
        }
    }

    // Method untuk checkout dengan data dummy
    public function checkout(Request $request)
    {
        // Data dummy untuk nomor meja dan pesanan
        $dataDummy = [
            'nomor_meja' => 5, // Contoh nomor meja
            'pesanan' => [
                [
                    'id_menu' => 1, // ID menu pertama
                    'kuantitas' => 2, // Jumlah
                    'harga' => 30000 // Harga per item
                ],
                [
                    'id_menu' => 2, // ID menu kedua
                    'kuantitas' => 1, // Jumlah
                    'harga' => 45000 // Harga per item
                ],
                [
                    'id_menu' => 3, // ID menu ketiga
                    'kuantitas' => 3, // Jumlah
                    'harga' => 25000 // Harga per item
                ],
            ],
        ];

        // Simulasi pemanggilan method createPesanan dengan data dummy
        $request->merge($dataDummy);
        return $this->createPesanan($request);
    }
}
