<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\ObjekWisataController;
use App\Http\Controllers\JenisTiketController;
use App\Http\Controllers\HargaTiketController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\DataPengunjungController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CheckoutController; 

// =========================================================================
//  --- A. RUTE PUBLIK / GUEST (TIDAK PERLU LOGIN) ---
// =========================================================================

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/katalog', [LandingController::class, 'katalog'])->name('wisata.katalog');
Route::get('/wisata/{id}', [LandingController::class, 'detail'])->name('wisata.detail');

Route::get('/checkout/{id_objek}', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/proses', [App\Http\Controllers\CheckoutController::class, 'proses'])->name('checkout.proses');
Route::get('/cek-pesanan', [App\Http\Controllers\CheckoutController::class, 'cekPesanan'])->name('cek-pesanan');

Route::post('/simulasi-bayar/{kode_pesanan}', [App\Http\Controllers\CheckoutController::class, 'simulasiBayar'])->name('simulasi.bayar');
// Rute Cetak E-Ticket
Route::get('/e-ticket/{kode_pesanan}', [App\Http\Controllers\CheckoutController::class, 'eTicket'])->name('cetak.eticket');

Route::middleware('guest')->group(function () {
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');



});


// =========================================================================
//  --- B. RUTE BACK-OFFICE / MANAJEMEN (WAJIB LOGIN) ---
// =========================================================================
Route::middleware('auth')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen User / Pegawai
    Route::resource('users', UserController::class);

    // 3. Manajemen Wilayah & Destinasi
    Route::resource('kabupatens', KabupatenController::class);
    Route::resource('objek-wisata', ObjekWisataController::class)->parameters([
        'objek-wisata' => 'objekWisata'
    ]);

    // Route hapus galeri via AJAX — letakkan SEBELUM resource agar tidak tertimpa
    Route::delete('/galeri-wisata/{id}', [ObjekWisataController::class, 'hapusGaleri'])->name('galeri.destroy');

    // 4. Manajemen Kategori & Harga Tiket
    Route::resource('jenis-tiket', JenisTiketController::class)->parameters([
        'jenis-tiket' => 'jenisTiket'
    ]);
    Route::resource('harga-tiket', HargaTiketController::class)->parameters([
        'harga-tiket' => 'hargaTiket'
    ]);

    // 5. Operasional Loket & Transaksi
    Route::resource('transaksi', TransaksiController::class);
    Route::get('riwayat-transaksi', [TransaksiController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('get-tiket/{id_objek}', [TransaksiController::class, 'getTiketByObjek'])->name('transaksi.getTiket');
    Route::put('/transaksi/{id}/void', [TransaksiController::class, 'void'])->name('transaksi.void');

    // 6. Validasi Tiket
    Route::get('validasi-tiket', [ValidasiController::class, 'index'])->name('validasi.index');
    Route::post('validasi-tiket/check', [ValidasiController::class, 'check'])->name('validasi.check');

    // 7. Data Pengunjung
    Route::get('data-pengunjung', [DataPengunjungController::class, 'index'])->name('data_pengunjung.index');

    // 8. Foto Wisata
    Route::get('/foto-wisata/{filename}', function ($filename) {
        $path = storage_path('app/public/wisata/' . $filename);
        if (!file_exists($path)) { abort(404); }
        return response()->file($path);
    })->name('foto.wisata');

    // 9. Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak-pengunjung', [LaporanController::class, 'cetakPengunjung'])->name('laporan.cetak-pengunjung');
    Route::get('/laporan/cetak-pendapatan', [LaporanController::class, 'cetakPendapatan'])->name('laporan.cetak-pendapatan');
    Route::get('/laporan/cetak-tiket', [LaporanController::class, 'cetakTiket'])->name('laporan.cetak-tiket');
    Route::get('/laporan/cetak-objek', [LaporanController::class, 'cetakObjek'])->name('laporan.cetak-objek');

    // 10. Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});