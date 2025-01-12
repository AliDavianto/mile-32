<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Log;

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
        // Query the Pesanan model with related data
        // Validate the request data
        // Get the latest pembayaran record
        $latestPembayaran = Pembayaran::latest()->first();

        // Check if there is any pembayaran record
        if (!$latestPembayaran) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan.',
            ], 404);
        }

        // Assign the latest id_pesanan
        $id_pesanan = $latestPembayaran->id_pesanan;

        // Query the Pesanan model with related data, filtered by id_pesanan
        $pesanan = Pesanan::with(['pembayaran', 'detailPesanan'])
            ->where('id_pesanan', $id_pesanan) // Filter by id_pesanan
            ->firstOrFail(); // Get the matching record or fail if not found
        // Transform the data into the desired format
        // Log::info('Pesanan yang ditemukan:', $pesanan);

        $data = [
            'id_pesanan' =>  $id_pesanan,  // Placeholder, you can use $pesanan->id_pesanan for real data
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

        // Log::info('Data yang akan dikirim:', $data);

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

    public function webhook(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY', 'your-default-key');  // Fetch from env
        $auth = base64_encode($serverKey . ':');
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth"
        ];

        Log::info('Received Webhook Request:', $request->all());

        $order_id = $request->input('order_id');
        $transaction_id = $request->input('transaction_id');
        $transaction_status = $request->input('transaction_status');

        if (!$order_id) {
            Log::error('Webhook request missing order_id');
            return response()->json(['error' => 'Missing order_id'], 400);
        }

        $payment = Pembayaran::where('id_pesanan', $order_id)->first();

        if (!$payment) {
            Log::error('Payment record not found for order_id:', ['order_id' => $order_id]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        if (in_array($payment->status_pembayaran, [Pembayaran::STATUS_SUCCESS])) {
            Log::info('Payment already processed:', ['order_id' => $order_id]);
            return response()->json('Payment already processed');
        }

        // Update status directly from webhook if available
        if ($transaction_status) {
            $this->updatePaymentStatus($payment, $transaction_status);
            return response()->json('success');
        }

        // Fetch details from Midtrans if transaction_status is not provided
        $url = "https://api.sandbox.midtrans.com/v2/{$order_id}/status";

        Log::info('Fetching Midtrans Transaction Details:', ['url' => $url]);

        try {
            $response = Http::withHeaders($headers)->withOptions(['verify' => false])->get($url);

            if ($response->failed()) {
                Log::error('Failed to fetch transaction from Midtrans', ['order_id' => $order_id]);
                return response()->json(['error' => 'Failed to fetch from Midtrans'], 500);
            }

            $responseData = $response->json();

            if (!isset($responseData['order_id'])) {
                Log::error('Invalid Midtrans API response');
                return response()->json(['error' => 'Invalid Midtrans response'], 500);
            }

            $this->updatePaymentStatus($payment, $responseData['transaction_status']);

            return response()->json('success');
        } catch (\Exception $e) {
            Log::error('Exception while processing webhook', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Unexpected error occurred'], 500);
        }
        // Log the incoming request
        //  Log::info('Webhook received:', $request->all());

        //  // Process the webhook here (e.g., update payment status)

        //  return response()->json(['message' => 'Webhook processed successfully']);
    }

    // Helper function to update payment status
    // Helper function to update payment status
    private function updatePaymentStatus($payment, $status)
    {
        // Update payment status based on the transaction status
        switch ($status) {
            case 'capture':
            case 'settlement':
                $payment->status_pembayaran = Pembayaran::STATUS_SUCCESS;
                break;
            case 'pending':
                $payment->status_pembayaran = Pembayaran::STATUS_PENDING;
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $payment->status_pembayaran = Pembayaran::STATUS_FAILED;
                break;
        }

        // Update the payment status
        $payment->save();
        Log::info('Payment updated:', [
            'order_id' => $payment->id_pesanan,
            'status' => $payment->status_pembayaran
        ]);

        // If payment status is success (3), update the order's status to 2
        if ($payment->status_pembayaran === Pembayaran::STATUS_SUCCESS) {
            $order = $payment->pesanan;
            $order->status_pesanan = 2;  // Update status_pesanan to 2
            $order->save();
            Log::info('Order status updated:', [
                'order_id' => $order->id_pesanan,
                'status_pesanan' => $order->status_pesanan
            ]);
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
        // Step 1: Get all `id_pesanan` with "Non-Digital" payment method and "menunggu" status
        $listIdPesananNonDigital = Pembayaran::where('metode_pembayaran', 'Non-Digital')
            ->where('status_pembayaran', 1)
            ->pluck('id_pesanan')
            ->toArray();

        // Step 2: Fetch orders (`Pesanan`) and their associated details
        $pesananData = Pesanan::with(['detailPesanan.menu']) // Load menu relationship
            ->whereIn('id_pesanan', $listIdPesananNonDigital)
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
                            'harga' => $detail->harga,
                        ];
                    }),
                ];
            });

        // Step 3: Pass the data to the view
        return view('dashboard_kasir', ['pesananData' => $pesananData]);
    }


    // Method untuk konfirmasi pesanan
    public function konfirmasiPesanan(Request $request)
    {
        // Validate the request
        $request->validate([
            'id_pesanan' => 'required|string',
            'status' => 'required|integer',
        ]);

        $pesanan = Pesanan::findOrFail($request->id_pesanan);

        if ($request->status == 1) {
            // Update for "Konfirmasi"
            $pesanan->pembayaran->update(['status_pembayaran' => 3]);
            $pesanan->update(['status_pesanan' => 2]);
        } elseif ($request->status == 4) {
            // Update for "Batal"
            $pesanan->pembayaran->update(['status_pembayaran' => 4]);
            $pesanan->update(['status_pesanan' => 4]);
        }
        return redirect()->route('getDashboardkasir');
    }

    public function batalPesanan(Request $request, $id_pesanan)
    {
        // Cari pesanan berdasarkan id_pesanan
        $pesanan = Pesanan::findOrFail($id_pesanan);

        // Perbarui status pembayaran dan pesanan
        $pesanan->pembayaran->update([
            'status_pembayaran' => 4,
        ]);
        $pesanan->update([
            'status_pesanan' => 4,
        ]);

        return redirect()->route('getDashboardkasir');
    }
}
