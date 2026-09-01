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
use App\Http\Controllers\BannerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventPublicController;
use App\Http\Controllers\VideoTerbaruController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\UlasanAdminController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\LanguageController;

// =========================================================================
//  --- A. RUTE PUBLIK / GUEST (TIDAK PERLU LOGIN) ---
// =========================================================================

Route::get('/', [LandingController::class, 'index'])->name('landing');

// Alih bahasa (switch locale)
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/katalog', [LandingController::class, 'katalog'])->name('wisata.katalog');
Route::get('/wisata/{id}', [LandingController::class, 'detail'])->name('wisata.detail');

Route::get('/berita', [BeritaPublicController::class, 'index'])->name('berita.index');

Route::get('/event', [EventPublicController::class, 'index'])->name('event.index');

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

Route::get('/checkout/{id_objek}', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
Route::get('/cek-pesanan', [CheckoutController::class, 'cekPesanan'])->name('cek-pesanan');
Route::get('/cek-status-pembayaran/{kode}', [CheckoutController::class, 'cekStatusAjax'])->name('checkout.cek-status-ajax');
Route::get('/snap-token/{kode}', [CheckoutController::class, 'snapTokenAjax'])->name('checkout.snap-token');

Route::get('/e-ticket/{kode_pesanan}', [CheckoutController::class, 'eTicket'])->name('cetak.eticket');

// API tier diskon (dipanggil JS, tidak perlu login)
Route::get('/api/diskon-tiers', [DiskonRombonganController::class, 'apiTiers'])->name('diskon.tiers');
Route::post('/api/cek-voucher', [CheckoutController::class, 'cekVoucher'])->name('voucher.cek');

// API Chatbot knowledge base
Route::get('/api/chatbot/knowledge', [App\Http\Controllers\ChatbotController::class, 'knowledge'])->name('chatbot.knowledge');

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
    Route::post('/wisata/{idObjek}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
    Route::post('/wisata/{idObjek}/wishlist', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist-saya', [WishlistController::class, 'index'])->name('wishlist.index');
});


// =========================================================================
//  --- B. RUTE BACK-OFFICE / MANAJEMEN (WAJIB LOGIN STAFF) ---
// =========================================================================
Route::middleware('auth')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen User — semua role dinas boleh akses, scoping wilayah dilakukan di controller.
    // kasir/petugas TIDAK boleh akses sama sekali (di luar cakupan mereka).
    Route::middleware('role:admin,kadis_provinsi,kadis_kabkota')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // 2a. Wilayah & Diskon Rombongan — hanya admin & kadis provinsi
    Route::middleware('role:admin,kadis_provinsi')->group(function () {
        Route::resource('kabupatens', KabupatenController::class)->except(['show']);
        Route::resource('diskon-rombongan', DiskonRombonganController::class)->except(['show'])->parameters([
            'diskon-rombongan' => 'diskonRombongan'
        ]);
    });

    // 2a-2/2b/2c. Kode Voucher, Banner, Event — hanya role dinas (admin, kadis_provinsi,
    // kadis_kabkota); kasir/petugas TIDAK boleh akses sama sekali (di luar cakupan mereka)
    Route::middleware('role:admin,kadis_provinsi,kadis_kabkota')->group(function () {
        // Kode Voucher — semua role dinas boleh lihat & tambah; edit/hapus
        // discoping ke wilayah sendiri untuk kadis_kabkota (lihat VoucherController)
        Route::resource('kelola-voucher', VoucherController::class)
            ->except(['show'])
            ->parameters(['kelola-voucher' => 'voucher']);

        // Manajemen Banner — semua role dinas boleh upload
        Route::resource('kelola-banner', BannerController::class)
            ->except(['show'])
            ->parameters(['kelola-banner' => 'banner']);

        // Manajemen Event — semua role dinas boleh upload
        Route::resource('kelola-event', EventController::class)
            ->except(['show'])
            ->parameters(['kelola-event' => 'event']);
    });

    // 2d. Manajemen Video Terbaru — singleton (cuma 1 data), hanya admin & kadis_provinsi
    Route::middleware('role:admin,kadis_provinsi')->group(function () {
        Route::get('/kelola-video', [VideoTerbaruController::class, 'edit'])->name('kelola-video.edit');
        Route::put('/kelola-video', [VideoTerbaruController::class, 'update'])->name('kelola-video.update');
    });

    // 3. Manajemen Destinasi (filter per-kabupaten dilakukan di controller) & Harga Tiket —
    // hanya role dinas, kasir/petugas TIDAK boleh akses sama sekali.
    Route::middleware('role:admin,kadis_provinsi,kadis_kabkota')->group(function () {
        Route::resource('objek-wisata', ObjekWisataController::class)->except(['show'])->parameters([
            'objek-wisata' => 'objekWisata'
        ]);

        Route::delete('/galeri-wisata/{id}', [ObjekWisataController::class, 'hapusGaleri'])->name('galeri.destroy');

        Route::resource('harga-tiket', HargaTiketController::class)->except(['show'])->parameters([
            'harga-tiket' => 'hargaTiket'
        ]);
    });

    // 4. Manajemen Kategori (master, hanya admin & kadis_provinsi)
    Route::middleware('role:admin,kadis_provinsi')->group(function () {
        Route::resource('jenis-tiket', JenisTiketController::class)->except(['show'])->parameters([
            'jenis-tiket' => 'jenisTiket'
        ]);
    });

    // 4b. Manajemen Berita — URI 'kelola-berita' (berbeda dari publik '/berita')
    // supaya tidak bentrok URL/nama route dengan halaman publik di bawah.
    // ->parameters() menjaga agar controller tetap pakai $berita, bukan $kelolaBerita.
    // Hanya role dinas — kasir/petugas tidak boleh akses.
    Route::middleware('role:admin,kadis_provinsi,kadis_kabkota')->group(function () {
        Route::resource('kelola-berita', BeritaController::class)
            ->except(['show'])
            ->parameters(['kelola-berita' => 'berita']);
    });

    // 4c. Moderasi Ulasan — hanya index & hapus (ulasan dibuat pengunjung, bukan admin).
    // Hanya role dinas — kasir/petugas tidak boleh akses.
    Route::middleware('role:admin,kadis_provinsi,kadis_kabkota')->group(function () {
        Route::get('/kelola-ulasan', [UlasanAdminController::class, 'index'])->name('kelola-ulasan.index');
        Route::delete('/kelola-ulasan/{ulasan}', [UlasanAdminController::class, 'destroy'])->name('kelola-ulasan.destroy');
    });

    // 5. Operasional Loket & Transaksi
    Route::resource('transaksi', TransaksiController::class)->except(['edit', 'update', 'destroy']);
    Route::get('riwayat-transaksi', [TransaksiController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('get-tiket/{id_objek}', [TransaksiController::class, 'getTiketByObjek'])->name('transaksi.getTiket');
    Route::put('/transaksi/{id}/void', [TransaksiController::class, 'void'])->name('transaksi.void');

    // QRIS Midtrans di Kasir (AJAX)
    Route::post('/transaksi/qris-pay', [TransaksiController::class, 'qrisStore'])->name('transaksi.qris.store');
    Route::post('/transaksi/qris-confirm/{id}', [TransaksiController::class, 'qrisConfirm'])->name('transaksi.qris.confirm');
    Route::post('/transaksi/qris-cancel/{id}', [TransaksiController::class, 'qrisCancel'])->name('transaksi.qris.cancel');

    // 6. Validasi Tiket
    Route::get('validasi-tiket', [ValidasiController::class, 'index'])->name('validasi.index');
    Route::post('validasi-tiket/check', [ValidasiController::class, 'check'])->name('validasi.check');

    // 7. Data Pengunjung
    Route::get('data-pengunjung', [DataPengunjungController::class, 'index'])->name('data_pengunjung.index');

    // 7b. Manajemen Pesanan Online (wajib login staff — sebelumnya bocor di rute publik)
    Route::get('/pesanan-online', [App\Http\Controllers\PesananOnlineController::class, 'index'])->name('pesanan-online.index');
    Route::get('/pesanan-online/{id}', [App\Http\Controllers\PesananOnlineController::class, 'show'])->name('pesanan-online.show');

    // 8. Foto Wisata
    Route::get('/foto-wisata/{filename}', function ($filename) {
        $path = storage_path('app/public/wisata/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    })->name('foto.wisata');

    // 9. Laporan — HANYA admin & kadis provinsi (kadis kabkota, kasir, petugas TIDAK boleh)
    Route::middleware('role:admin,kadis_provinsi')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak-pengunjung', [LaporanController::class, 'cetakPengunjung'])->name('laporan.cetak-pengunjung');
        Route::get('/laporan/cetak-offline', [LaporanController::class, 'cetakOffline'])->name('laporan.cetak-offline');
        Route::get('/laporan/cetak-online', [LaporanController::class, 'cetakOnline'])->name('laporan.cetak-online');
        Route::get('/laporan/cetak-ulasan', [LaporanController::class, 'cetakUlasan'])->name('laporan.cetak-ulasan');
        Route::get('/laporan/cetak-tren', [LaporanController::class, 'cetakTren'])->name('laporan.cetak-tren');
        Route::get('/laporan/cetak-rekap-tahunan', [LaporanController::class, 'cetakRekapTahunan'])->name('laporan.cetak-rekap-tahunan');
        Route::get('/laporan/cetak-validasi', [LaporanController::class, 'cetakValidasi'])->name('laporan.cetak-validasi');
        Route::get('/laporan/cetak-publikasi', [LaporanController::class, 'cetakPublikasi'])->name('laporan.cetak-publikasi');
        Route::get('/laporan/cetak-voucher', [LaporanController::class, 'cetakVoucher'])->name('laporan.cetak-voucher');
        Route::get('/laporan/cetak-wishlist', [LaporanController::class, 'cetakWishlist'])->name('laporan.cetak-wishlist');
        Route::get('/laporan/cetak-pendapatan', [LaporanController::class, 'cetakPendapatan'])->name('laporan.cetak-pendapatan');
        Route::get('/laporan/cetak-tiket', [LaporanController::class, 'cetakTiket'])->name('laporan.cetak-tiket');
        Route::get('/laporan/cetak-objek', [LaporanController::class, 'cetakObjek'])->name('laporan.cetak-objek');
        Route::get('/laporan/cetak-master', [LaporanController::class, 'cetakMaster'])->name('laporan.cetak-master');

        // Export Excel
        Route::get('/laporan/export-pengunjung', [LaporanController::class, 'exportPengunjung'])->name('laporan.export-pengunjung');
        Route::get('/laporan/export-offline', [LaporanController::class, 'exportOffline'])->name('laporan.export-offline');
        Route::get('/laporan/export-online', [LaporanController::class, 'exportOnline'])->name('laporan.export-online');
        Route::get('/laporan/export-ulasan', [LaporanController::class, 'exportUlasan'])->name('laporan.export-ulasan');
        Route::get('/laporan/export-tren', [LaporanController::class, 'exportTren'])->name('laporan.export-tren');
        Route::get('/laporan/export-rekap-tahunan', [LaporanController::class, 'exportRekapTahunan'])->name('laporan.export-rekap-tahunan');
        Route::get('/laporan/export-validasi', [LaporanController::class, 'exportValidasi'])->name('laporan.export-validasi');
        Route::get('/laporan/export-publikasi', [LaporanController::class, 'exportPublikasi'])->name('laporan.export-publikasi');
        Route::get('/laporan/export-voucher', [LaporanController::class, 'exportVoucher'])->name('laporan.export-voucher');
        Route::get('/laporan/export-wishlist', [LaporanController::class, 'exportWishlist'])->name('laporan.export-wishlist');
        Route::get('/laporan/export-pendapatan', [LaporanController::class, 'exportPendapatan'])->name('laporan.export-pendapatan');
        Route::get('/laporan/export-tiket', [LaporanController::class, 'exportTiket'])->name('laporan.export-tiket');
        Route::get('/laporan/export-objek', [LaporanController::class, 'exportObjek'])->name('laporan.export-objek');
        Route::get('/laporan/export-master', [LaporanController::class, 'exportMaster'])->name('laporan.export-master');
    }); // end grup Laporan — admin & kadis provinsi saja

    // 9b. Rangking Wisata — admin, kadis_provinsi, kadis_kabkota
    Route::middleware('role:admin,kadis_provinsi,kadis_kabkota')->group(function () {
        Route::get('/rangking-wisata', [\App\Http\Controllers\RangkingWisataController::class, 'index'])->name('rangking.index');
    });

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
