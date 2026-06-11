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

// =========================================================================
//  --- A. RUTE PUBLIK / GUEST (TIDAK PERLU LOGIN) ---
// =========================================================================

// Halaman Utama, Katalog Lengkap & Detail Wisata
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/katalog', [LandingController::class, 'katalog'])->name('wisata.katalog');
Route::get('/wisata/{id}', [LandingController::class, 'detail'])->name('wisata.detail');

// Rute Otentikasi Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});


// =========================================================================
//  --- B. RUTE BACK-OFFICE / MANAJEMEN (WAJIB LOGIN) ---
// =========================================================================
Route::middleware('auth')->group(function () {
    
    // 1. Dashboard Utama (URL diubah ke /dashboard agar tidak bentrok dengan Landing Page)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen User / Pegawai
    Route::resource('users', UserController::class);

    // 3. Manajemen Wilayah & Destinasi
    Route::resource('kabupatens', KabupatenController::class);
    Route::resource('objek-wisata', ObjekWisataController::class)->parameters([
        'objek-wisata' => 'objekWisata' 
    ]);

    // 4. Manajemen Kategori & Harga Tiket
    Route::resource('jenis-tiket', JenisTiketController::class)->parameters([
        'jenis-tiket' => 'jenisTiket' 
    ]);
    Route::resource('harga-tiket', HargaTiketController::class)->parameters([
        'harga-tiket' => 'hargaTiket'
    ]);

    // 5. Operasional Loket & Transaksi (Kasir)
    Route::resource('transaksi', TransaksiController::class);
    Route::get('riwayat-transaksi', [TransaksiController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('get-tiket/{id_objek}', [TransaksiController::class, 'getTiketByObjek'])->name('transaksi.getTiket');
    Route::put('/transaksi/{id}/void', [TransaksiController::class, 'void'])->name('transaksi.void');

    // 6. Validasi Tiket (Scanner Pintu Masuk)
    Route::get('validasi-tiket', [ValidasiController::class, 'index'])->name('validasi.index');
    Route::post('validasi-tiket/check', [ValidasiController::class, 'check'])->name('validasi.check');

    // 7. Data Riwayat Pengunjung
    Route::get('data-pengunjung', [DataPengunjungController::class, 'index'])->name('data_pengunjung.index');

    // 8. Manajemen File Foto Wisata
    Route::get('/foto-wisata/{filename}', function ($filename) {
        $path = storage_path('app/public/wisata/' . $filename);
        if (!file_exists($path)) { abort(404); }
        return response()->file($path);
    })->name('foto.wisata');

    // 9. Modul Cetak Laporan Manajerial
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak-pengunjung', [LaporanController::class, 'cetakPengunjung'])->name('laporan.cetak-pengunjung');
    Route::get('/laporan/cetak-pendapatan', [LaporanController::class, 'cetakPendapatan'])->name('laporan.cetak-pendapatan');
    Route::get('/laporan/cetak-tiket', [LaporanController::class, 'cetakTiket'])->name('laporan.cetak-tiket');
    Route::get('/laporan/cetak-objek', [LaporanController::class, 'cetakObjek'])->name('laporan.cetak-objek');

    // 10. Keluar Sistem
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});