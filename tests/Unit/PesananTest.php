<?php

namespace Tests\Unit;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesananTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function Create_Pesanan()
    {
        // Membuat data dummy untuk Pesanan
        $pesanan = Pesanan::create([
            'nomor_meja' => 5,
            'waktu_pemesanan' => now(),
            'status_pesanan' => 'menunggu',
            'total_pembayaran' => 100000,
        ]);

        // Memastikan data tersimpan dengan benar
        $this->assertDatabaseHas('pesanans', [
            'nomor_meja' => 5,
            'status_pesanan' => 'menunggu',
            'total_pembayaran' => 100000,
        ]);
    }

    /** @test */
    public function Update_Pesanan()
    {
        // Membuat data dummy untuk Pesanan
        $pesanan = Pesanan::create([
            'nomor_meja' => 3,
            'waktu_pemesanan' => now(),
            'status_pesanan' => 'menunggu',
            'total_pembayaran' => 50000,
        ]);

        // Update status pesanan
        $pesanan->status_pesanan = 'diproses';
        $pesanan->save();

        // Memastikan status pesanan terupdate
        $this->assertEquals('diproses', $pesanan->status_pesanan);

        // Update lagi status pesanan ke 'selesai'
        $pesanan->status_pesanan = 'selesai';
        $pesanan->save();

        // Memastikan status pesanan terupdate
        $this->assertEquals('selesai', $pesanan->status_pesanan);
    }

    /** @test */
    public function Validasi_data_pesanan_kosong()
    {
        // Mencoba membuat Pesanan dengan field kosong
        $this->expectException(\Illuminate\Database\QueryException::class);

        Pesanan::create([
            'nomor_meja' => null, // nomor_meja kosong
            'waktu_pemesanan' => null,   // waktu_pemesanan kosong
            'status_pesanan' => null,    // status_pesanan kosong
            'total_pembayaran' => null,  // total_pembayaran kosong
        ]);
    }

    /** @test */
    public function validasi_salah_input()
    {
        // Mencoba membuat Pesanan dengan total pembayaran tidak valid
        $this->expectException(\Illuminate\Database\QueryException::class);

        Pesanan::create([
            'nomor_meja' => 2,
            'waktu_pemesanan' => now(),
            'status_pesanan' => 'menunggu',
            'total_pembayaran' => 'invalid_payment', // total pembayaran tidak valid
        ]);
    }

    /** @test */
    public function Validasi_untuk_beberapa_pesanan()
    {
        // Membuat instance Pesanan
        $pesanan = Pesanan::factory()->create();

        // Membuat beberapa detail pesanan terkait dengan pesanan
        $detailPesanan1 = DetailPesanan::factory()->create(['id_pesanan' => $pesanan->id_pesanan]);
        $detailPesanan2 = DetailPesanan::factory()->create(['id_pesanan' => $pesanan->id_pesanan]);

        // Mengambil detail pesanan melalui relasi
        $this->assertTrue($pesanan->detailPesanan->contains($detailPesanan1));
        $this->assertTrue($pesanan->detailPesanan->contains($detailPesanan2));

        // Memastikan jumlah detail pesanan terkait
        $this->assertEquals(2, $pesanan->detailPesanan->count());
    }

    /** @test */
    public function Validasi_satu_pesanan_satu_pembayaran()
    {
        // Membuat instance Pesanan
        $pesanan = Pesanan::factory()->create();

        // Membuat pembayaran terkait dengan pesanan
        $pembayaran = Pembayaran::factory()->create(['id_pesanan' => $pesanan->id_pesanan]);

        // Mengambil pembayaran melalui relasi
        $this->assertEquals($pembayaran->id_pesanan, $pesanan->pembayaran->id_pesanan);
        $this->assertNotNull($pesanan->pembayaran);
    }

    /** @test */
    public function validasi_data_dapat_diisi_sesuai()
    {
        // Membuat instance pesanan baru
        $pesanan = new Pesanan();

        // Mendapatkan atribut yang dapat diisi
        $fillable = ['nomor_meja', 'waktu_pemesanan', 'status_pesanan', 'total_pembayaran'];

        // Memastikan bahwa atribut yang dapat diisi sesuai dengan yang didefinisikan
        $this->assertEquals($fillable, $pesanan->getFillable());
    }
}
