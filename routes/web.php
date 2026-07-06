<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengunjungAuthController;
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
use App\Http\Controllers\DiskonRombonganController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BeritaPublicController;

// =========================================================================
//  --- A. RUTE PUBLIK / GUEST (TIDAK PERLU LOGIN) ---
// =========================================================================

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/katalog', [LandingController::class, 'katalog'])->name('wisata.katalog');
Route::get('/wisata/{id}', [LandingController::class, 'detail'])->name('wisata.detail');

Route::get('/berita', [BeritaPublicController::class, 'index'])->name('berita.index');

Route::get('/checkout/{id_objek}', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
Route::get('/cek-pesanan', [CheckoutController::class, 'cekPesanan'])->name('cek-pesanan');

Route::post('/simulasi-bayar/{kode_pesanan}', [CheckoutController::class, 'simulasiBayar'])->name('simulasi.bayar');
Route::get('/e-ticket/{kode_pesanan}', [CheckoutController::class, 'eTicket'])->name('cetak.eticket');

// API tier diskon (dipanggil JS, tidak perlu login)
Route::get('/api/diskon-tiers', [DiskonRombonganController::class, 'apiTiers'])->name('diskon.tiers');

// Manajemen Pesanan Online (Admin)
Route::get('/pesanan-online', [App\Http\Controllers\PesananOnlineController::class, 'index'])->name('pesanan-online.index');
Route::get('/pesanan-online/{id}', [App\Http\Controllers\PesananOnlineController::class, 'show'])->name('pesanan-online.show');

// ── Auth Staff (Admin/Kadis/Kasir/Petugas) ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

// ── Auth Pengunjung (guard terpisah dari staff) ──
Route::get('/daftar', [PengunjungAuthController::class, 'showRegisterForm'])->name('pengunjung.register.form');
Route::post('/daftar', [PengunjungAuthController::class, 'register'])->name('pengunjung.register');
Route::get('/masuk', [PengunjungAuthController::class, 'showLoginForm'])->name('pengunjung.login');
Route::post('/masuk', [PengunjungAuthController::class, 'login'])->name('pengunjung.login.proses');
Route::post('/keluar', [PengunjungAuthController::class, 'logout'])->name('pengunjung.logout');

Route::middleware('pengunjung')->group(function () {
    Route::get('/riwayat-pesanan', [PengunjungAuthController::class, 'riwayat'])->name('pengunjung.riwayat');
});


// =========================================================================
//  --- B. RUTE BACK-OFFICE / MANAJEMEN (WAJIB LOGIN STAFF) ---
// =========================================================================
Route::middleware('auth')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen User, Wilayah & Diskon Rombongan — hanya admin & kadis provinsi
    Route::middleware('role:admin,kadis_provinsi')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('kabupatens', KabupatenController::class);
        Route::resource('diskon-rombongan', DiskonRombonganController::class)->parameters([
            'diskon-rombongan' => 'diskonRombongan'
        ]);
    });

    // 3. Manajemen Destinasi (filter per-kabupaten dilakukan di controller)
    Route::resource('objek-wisata', ObjekWisataController::class)->parameters([
        'objek-wisata' => 'objekWisata'
    ]);

    Route::delete('/galeri-wisata/{id}', [ObjekWisataController::class, 'hapusGaleri'])->name('galeri.destroy');

    // 4. Manajemen Kategori & Harga Tiket
    Route::resource('jenis-tiket', JenisTiketController::class)->parameters([
        'jenis-tiket' => 'jenisTiket'
    ]);
    Route::resource('harga-tiket', HargaTiketController::class)->parameters([
        'harga-tiket' => 'hargaTiket'
    ]);

    // 4b. Manajemen Berita — URI 'kelola-berita' (berbeda dari publik '/berita')
    // supaya tidak bentrok URL/nama route dengan halaman publik di bawah.
    // ->parameters() menjaga agar controller tetap pakai $berita, bukan $kelolaBerita.
    Route::resource('kelola-berita', BeritaController::class)
        ->except(['show'])
        ->parameters(['kelola-berita' => 'berita']);

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
    Route::get('/laporan/cetak-master', [LaporanController::class, 'cetakMaster'])->name('laporan.cetak-master');

    // 10. Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// =========================================================================
//  --- C. RUTE PUBLIK WILDCARD (HARUS DI PALING BAWAH) ---
//  Route dengan pola {slug}/{id} satu segmen HARUS diletakkan setelah semua
//  route statis (termasuk resource admin), supaya tidak "mencuri" request
//  seperti /berita/create sebelum sampai ke route admin yang dituju.
// =========================================================================
Route::get('/berita/{slug}', [BeritaPublicController::class, 'detail'])->name('berita.detail');