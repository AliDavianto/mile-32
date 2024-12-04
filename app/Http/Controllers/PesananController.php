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

    public function createIdPembayaran($nomorMeja, $metodePembayaran)
    {
        // Get the current date in the format YYYYMMDD
        $currentDate = now()->format('Ymd');

        // Retrieve the latest payment for today's date from the database
        $latestPayment = Pembayaran::whereDate('created_at', today())
            ->orderBy('id_pembayaran', 'desc')
            ->first();

        // Extract the sequence number from the latest payment, if available
        if ($latestPayment) {
            // Assuming the ID format is PMXXYYDYYYYMMDD or PMXXYYNDYYYYMMDD
            $lastSequence = intval(substr($latestPayment->id_pembayaran, 4, 2));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        // Format the sequence number as a 2-digit string
        $sequenceNumber = str_pad($nextSequence, 2, '0', STR_PAD_LEFT);

        // Format the table number as a 2-digit string
        $formattedNomorMeja = str_pad($nomorMeja, 2, '0', STR_PAD_LEFT);
        Log::info('Metode Pembayaran:',  ['metode_pembayaran' => $metodePembayaran]);
        // Determine the suffix based on payment method
        if ($metodePembayaran === "Digital") {
            $suffix = 'D';
        } elseif ($metodePembayaran === "Non-Digital") {
            $suffix = 'ND';
        } else {
            throw new \InvalidArgumentException("Invalid payment method: $metodePembayaran");
        }

        // Combine components to form the ID
        $idPembayaran = 'PM' . $formattedNomorMeja . $sequenceNumber . $suffix . $currentDate;

        return $idPembayaran;
    }



    // Method untuk entry ke tabel pesanan
    public function createPesanan(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'nomor_meja' => 'required|integer',
            'pesanan' => 'required|array',
            'pesanan.*.id_menu' => 'required|string',
            'pesanan.*.kuantitas' => 'required|integer|min:1',
            'pesanan.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string',
        ]);

        Log::info('Data pesanan yang diterima:', $validatedData);
        $id_pesanan = $this->createIdPesanan($validatedData['nomor_meja']);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Simpan data ke tabel Pesanan
            $pesanan = Pesanan::create([
                'id_pesanan' => $id_pesanan,
                'nomor_meja' => $validatedData['nomor_meja'],
                'waktu_pemesanan' => now(),
                'status_pesanan' => 1,
                'total_pembayaran' => $validatedData['total_harga'],
            ]);

            // Panggil method untuk menyimpan detail pesanan dan pembayaran
            $this->createDetailPesanan($validatedData['pesanan'], $id_pesanan, $validatedData['nomor_meja']);
            $this->createPembayaran($id_pesanan, $validatedData['nomor_meja'], $validatedData['total_harga'], $validatedData['metode_pembayaran']);

            // Commit transaksi jika semua berhasil
            DB::commit();
            Log::info('Udah di commit:');
            // Redirect to the pembayaran route, passing id_pesanan
            // Create a new instance of the PembayaranController
            // $pembayaranController = new PembayaranController();

            // // Call the pembayaran method
            // $response = $pembayaranController->pembayaran(new Request(['id_pesanan' => $id_pesanan]));

            // Return the response from the pembayaran method
            return response()->json([
                'message' => 'Pesanan Berhasil dicatat',
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

    protected function createMetodePembayaran(string $metodePembayaran)
    {
        Log::info('Metode Pembayaran yang diterima dari localstorage:', ['metode_pembayaran' => $metodePembayaran]);
        if ($metodePembayaran === "1") {
            return "Digital";
        } else {
            return "Non-Digital";
        }
    }

    protected function createPembayaran(string $id_pesanan, int $nomorMeja, int $total_pembayaran, string $metodePembayaran)
    {
        $metode_pembayaran = $this->createMetodePembayaran($metodePembayaran);
        $id_pembayaran = $this->createIdPembayaran($nomorMeja, $metode_pembayaran);

        Pembayaran::create([
            'id_pembayaran' => $id_pembayaran,
            'id_pesanan' => $id_pesanan, // Correctly references the valid id_pesanan
            'total_pembayaran' =>  $total_pembayaran,
            'metode_pembayaran' => $metode_pembayaran,
            'status_pembayaran' => 1,
            'waktu_transaksi' => now(),
        ]);
    }

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

    public function getPesananPembayaranBerhasil()
    {
        // Step 1: Get all `id_pesanan` where `status_pembayaran === 2`
        $listIdPesanan = Pesanan::where('status_pesanan', 2)->pluck('id_pesanan')->toArray();

        // Step 2: Fetch orders (`Pesanan`) and their associated details
        $pesananData = Pesanan::with(['detailPesanan.menu']) // Load `menu` relationship via `detailPesanan`
            ->whereIn('id_pesanan', $listIdPesanan)
            ->get()
            ->map(function ($pesanan) {
                return [
                    'id_pesanan' => $pesanan->id_pesanan,
                    'nomor_meja' => $pesanan->nomor_meja,
                    'total_harga' => $pesanan->total_pembayaran,
                    'pesanan' => $pesanan->detailPesanan->map(function ($detail) {
                        return [
                            'id_menu' => $detail->menu->id_menu,
                            'nama_produk' => $detail->menu->nama_produk, // Fetch `nama_produk` from the `menu` table
                            'kuantitas' => $detail->kuantitas,
                        ];
                    })->toArray(), // Convert nested data to an array
                ];
            })->toArray(); // Convert collection to an array

        // Step 3: Return or pass the data (returning here for demonstration purposes)
        // Return the view with the data
        return view('dashboard_staff', compact('pesananData'));
    }

    public function dashboardstaff(Request $request)
{
    $idPesanan = $request->input('id_pesanan');

    // Process the request (e.g., mark the order as completed)
    $pesanan = Pesanan::find($idPesanan);
    if ($pesanan) {
        $pesanan->status_pesanan = 3; // Assuming 3 means "completed"
        $pesanan->save();

        return redirect()->route('getDashboardstaff');
    }

    return response()->json(['message' => 'Pesanan tidak ditemukan!'], 404);
}
}
