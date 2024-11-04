<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    public function index()
    {
            $pesanans = Pesanan::all();
            return view('pesanans.index', compact('pesanans'));
    }
    // Method untuk entry ke tabel pesanan
    public function createPesanan(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'nomor_meja' => 'required|integer',
            'pesanan' => 'required|array',
            'pesanan.*.id_menu' => 'required|integer',
            'pesanan.*.kuantitas' => 'required|integer|min:1', // Changed kuantitas to quantity
            'pesanan.*.harga' => 'required|numeric|min:0', // Added validation for 'harga'
            'total_harga' => 'required|numeric|min:0',
        ]);

        // Logging data permintaan
        Log::info('Data pesanan yang diterima:', $validatedData);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Simpan data ke tabel Pesanan
            $pesanan = Pesanan::create([
                'nomor_meja' => $validatedData['nomor_meja'],
                'waktu_pemesanan' => now(),
                'status_pesanan' => 'menunggu', // atau status yang diinginkan
                'total_pembayaran' => $validatedData['total_harga'],
            ]);

            // Dapatkan id_pesanan dari pesanan yang baru disimpan
            $id_pesanan = $pesanan->id_pesanan;

            // Panggil method untuk menyimpan detail pesanan
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
                'kuantitas' => $item['kuantitas'], // Changed kuantitas to quantity
                'harga' => $item['harga'],
            ]);
        }
    }

    // Method untuk checkout
    public function checkout(Request $request)
    {
        // Simpan data ke tabel Pesanan
        // Panggil fungsi createPesanan dengan data request
        $pesanan = $this->createPesanan($request);

        // Kembalikan respons
        return response()->json($pesanan, 201);
    }
    {
        public function update(Request $request, $id)
        {
            $request->validate([
                'id_keranjang' => 'required|integer',
                'nomor_meja' => 'required|integer|max:999',
                'waktu_pemesanan' => 'required|date',
                'status_pesanan' => 'required|in:menunggu,diproses,selesai',
                'total_pembayaran' => 'required|integer',
            ]);
    
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->update($request->all());
    
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diperbarui.',
                'data' => $pesanan
            ], 200);
        }
    
        public function destroy($id)
        {
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus.'
            ], 200);
        }
    }
}
