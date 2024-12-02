<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
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

    public function createIdPesanan($nomorMeja)
    {
        // Get the current date in the format YYYYMMDD
        $currentDate = now()->format('Ymd');

        // Retrieve the latest order for today's date from the database
        $latestOrder = Pesanan::whereDate('created_at', today())
            ->orderBy('id_pesanan', 'desc')
            ->first();

        // Extract the sequence number from the latest order, if available
        if ($latestOrder) {
            // Assuming the ID format is PMXX000YYYYMMDD
            // Extract sequence number (characters 4, 3 length)
            $lastSequence = intval(substr($latestOrder->id_pesanan, 4, 3));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        // Format the sequence number with leading zeros (e.g., '001')
        $sequenceNumber = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

        // Combine components to form the ID
        $idPesanan = 'PM' . $nomorMeja . $sequenceNumber . $currentDate;

        return $idPesanan;
    }

    public function createDetailIdPesanan($nomorMeja)
    {
        // Get the current date in the format YYYYMMDD
        $currentDate = now()->format('Ymd');

        // Retrieve the latest detail order for today's date from the database
        $latestDetailOrder = DetailPesanan::whereDate('created_at', today())
            ->orderBy('id_detail_pesanan', 'desc')
            ->first();

        // Extract the sequence number from the latest detail order, if available
        if ($latestDetailOrder) {
            // Assuming the ID format is DPMXX0001YYYYMMDD
            $lastSequence = intval(substr($latestDetailOrder->id_detail_pesanan, 5, 4));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        // Format the sequence number as a 4-digit string
        $sequenceNumber = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

        // Combine components to form the ID
        $idDetailPesanan = 'DPM' . str_pad($nomorMeja, 2, '0', STR_PAD_LEFT) . $sequenceNumber . $currentDate;

        return $idDetailPesanan;
    }

    public function createIdPembayaran($nomorMeja)
    {
        // Get the current date in the format YYYYMMDD
        $currentDate = now()->format('Ymd');

        // Retrieve the latest payment for today's date from the database
        $latestPayment = Pembayaran::whereDate('created_at', today())
            ->orderBy('id_pembayaran', 'desc')
            ->first();

        // Extract the sequence number from the latest payment, if available
        if ($latestPayment) {
            // Assuming the ID format is PMXXYYDYYYYMMDD
            $lastSequence = intval(substr($latestPayment->id_pembayaran, 4, 2));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        // Format the sequence number as a 2-digit string
        $sequenceNumber = str_pad($nextSequence, 2, '0', STR_PAD_LEFT);

        // Format the table number as a 2-digit string
        $formattedNomorMeja = str_pad($nomorMeja, 2, '0', STR_PAD_LEFT);

        // Combine components to form the ID
        $idPembayaran = 'PM' . $formattedNomorMeja . $sequenceNumber . 'D' . $currentDate;

        return $idPembayaran;
    }


    // Method untuk entry ke tabel pesanan
    public function createPesanan(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'nomor_meja' => 'required|integer',
            'pesanan' => 'required|array',
            'pesanan.*.id_menu' => 'required|string', // Changed to string for id_menu
            'pesanan.*.kuantitas' => 'required|integer|min:1',
            'pesanan.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
        ]);

        // // Logging data permintaan
        Log::info('Data pesanan yang diterima:', $validatedData);
        $id_pesanan = $this->createIdPesanan($validatedData['nomor_meja']);
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Simpan data ke tabel Pesanan
            $pesanan = Pesanan::create([
                'id_pesanan' =>  $id_pesanan,
                'nomor_meja' => $validatedData['nomor_meja'],
                'waktu_pemesanan' => now(),
                'status_pesanan' => 1, // atau status yang diinginkan
                'total_pembayaran' =>  $validatedData['total_harga'],
            ]);

            // Panggil method untuk menyimpan detail pesanan
            $this->createDetailPesanan($validatedData['pesanan'], $id_pesanan, $validatedData['nomor_meja']);
            $this->createPembayaran($id_pesanan, $validatedData['nomor_meja'],  $validatedData['total_harga']);
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
    protected function createDetailPesanan(array $pesanan, string $id_pesanan, int $nomorMeja)
    {
        foreach ($pesanan as $item) {
            $id_detail_pesanan = $this->createDetailIdPesanan($nomorMeja);
            DetailPesanan::create([
                'id_detail_pesanan' => $id_detail_pesanan,
                'id_pesanan' => $id_pesanan, // Correctly references the valid id_pesanan
                'id_menu' => $item['id_menu'],
                'kuantitas' => $item['kuantitas'],
                'harga' => $item['harga'],
            ]);
        }
    }

    protected function createPembayaran(string $id_pesanan, int $nomorMeja, int $total_pembayaran)
    {
        $id_pembayaran = $this->createIdPembayaran($nomorMeja);

        Pembayaran::create([
            'id_pembayaran' => $id_pembayaran,
            'id_pesanan' => $id_pesanan, // Correctly references the valid id_pesanan
            'total_pembayaran' =>  $total_pembayaran,
            'metode_pembayaran' => 'Digital',
            'status_pembayaran' => 1,
            'waktu_transaksi' => now(),
        ]);
    }

    // Method untuk checkout
    // public function checkout(Request $request)
    // {
    //     // Simpan data ke tabel Pesanan
    //     // Panggil fungsi createPesanan dengan data request
    //    $this->createPesanan($request);

    //     // Kembalikan respons
    //     return response()->json( "createPesanan dipanggil wkwkwkwkwkwk", 201);
    // }
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
