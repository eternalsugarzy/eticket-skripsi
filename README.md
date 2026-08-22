# E-Ticketing Kalsel

Sistem tiket online untuk objek wisata di Provinsi Kalimantan Selatan — dibangun untuk Dinas Pariwisata Provinsi Kalimantan Selatan. Skripsi/tugas akhir.

## Fitur Utama

- Pemesanan tiket online tanpa harus punya akun (opsional daftar untuk lihat riwayat pesanan)
- Pembayaran online via Midtrans (Snap)
- **[BARU]** Pembayaran QRIS Statis langsung di layar kasir offline/loket.
- E-Ticket dengan QR Code + validasi/scan tiket di lokasi
- Diskon rombongan & kode voucher
- Notifikasi email & WhatsApp (Fonnte)
- Manajemen objek wisata, harga tiket, banner, berita, dan event
- Multi-role: Admin, Kadis Provinsi, Kadis Kab/Kota, Kasir, Petugas — masing-masing dengan hak akses berbeda
- Laporan & rekapitulasi (cetak PDF & export Excel)
- **[BARU]** AI Customer Service Chatbot (NLP berbasis keyword/rule-based) untuk menjawab FAQ, info jam operasional, harga tiket, dan rute perjalanan (Google Maps).
- **[BARU]** Dukungan Alih Bahasa / Multibahasa (Bahasa Indonesia 🇮🇩 & English 🇬🇧) untuk wisatawan mancanegara.

## Teknologi

- Laravel 12 (PHP 8.2+)
- MySQL
- Bootstrap 5
- Midtrans Payment Gateway
- Fonnte (WhatsApp Gateway)
- **[BARU]** Rule-based NLP Engine (Vanilla JS) untuk Chatbot AI

## Instalasi

```bash
git clone <url-repo-ini>
cd eticket-skripsi

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Atur koneksi database, kredensial Midtrans, dan token Fonnte di file `.env`, lalu:

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Buka `http://localhost:8000`.

## Lisensi

Proyek ini dibuat untuk keperluan tugas akhir/skripsi.
