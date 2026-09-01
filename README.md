<div align="center">
  <img src="https://img.freepik.com/free-vector/travel-tourism-illustration-with-resort-sightseeing-elements_1284-30189.jpg" alt="Logo" width="200"/>

  # 🌴 E-Ticketing Kalsel
  **Sistem Informasi Terpadu & Pemesanan Tiket Destinasi Wisata Provinsi Kalimantan Selatan**

  [![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
  [![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
  [![Midtrans](https://img.shields.io/badge/Midtrans-006399?style=for-the-badge&logo=stripe&logoColor=white)](https://midtrans.com)
</div>

<br/>

Sistem *E-Ticketing Kalsel* adalah platform digital komprehensif yang dirancang untuk Dinas Pariwisata Provinsi Kalimantan Selatan. Aplikasi ini mempermudah wisatawan (lokal maupun mancanegara) dalam merencanakan kunjungan, memesan tiket, serta memberikan ulasan, sekaligus memberikan alat *back-office* yang kuat bagi pemerintah dan pengelola wisata untuk melacak statistik, pendapatan, dan kepuasan pengunjung secara *real-time*.

---

## ✨ Fitur Unggulan

### 🧑‍💻 Untuk Wisatawan (Frontend)
- **⚡ Checkout Cepat Tanpa Akun:** Beli tiket dalam hitungan detik tanpa dipaksa membuat akun (opsional pendaftaran untuk mencatat Riwayat Pesanan & Wishlist).
- **💳 Pembayaran Terintegrasi (Midtrans):** Mendukung pembayaran via QRIS, Bank Transfer, dan E-Wallet secara otomatis dan aman (Snap API).
- **🎟️ E-Ticket Pintar:** Tiket digital dilengkapi dengan QR Code presisi tinggi. Mendukung masa kedaluwarsa dinamis berdasarkan waktu operasional & tanggal kunjungan.
- **🤖 AI Chatbot Asisten:** Chatbot pintar (*Rule-based NLP*) yang siap menjawab pertanyaan seputar harga, jam buka, FAQ, hingga navigasi rute Google Maps secara instan.
- **🌍 Multilingual (Bilingual):** Mendukung penuh **Bahasa Indonesia 🇮🇩** dan **English 🇬🇧** untuk mengakomodasi turis asing.
- **💬 Ulasan & Rating:** Berikan *feedback* untuk objek wisata yang telah dikunjungi guna membantu wisatawan lain.

### 🏢 Untuk Pengelola & Dinas (Backend)
- **👥 Multi-Role Authorization:** Dilengkapi manajemen hak akses ketat untuk **Admin**, **Kadis Provinsi**, **Kadis Kabupaten/Kota** (Scoping wilayah), **Kasir**, dan **Petugas Validasi**.
- **📊 Dashboard & Peringkat Wisata:** Menyajikan statistik *real-time* dan fitur peringkat popularitas (Ranking) dari seluruh destinasi di Kalsel, menggabungkan data penjualan loket & web.
- **🏷️ Diskon & Voucher:** Atur tier diskon rombongan otomatis (berdasarkan jumlah pax) serta kode kupon/voucher diskon secara dinamis.
- **🏪 Modul Kasir Interaktif (POS):** Kasir *offline* terintegrasi dengan fitur **QRIS Dinamis** (Midtrans) sehingga pembayaran di tempat bisa diotomatisasi.
- **📱 Validasi QR Instan:** Petugas pintu masuk dapat memindai tiket (scan QR) yang secara cerdas mendeteksi *tiket lunas*, *sudah terpakai*, atau *kedaluwarsa*.
- **📈 Pelaporan Komprehensif:** Laporan otomatis yang bisa diekspor dalam format Excel (Spreadsheet) dan Cetak PDF.

---

## 🛠️ Teknologi yang Digunakan

Aplikasi ini dikembangkan menggunakan *stack* teknologi modern untuk menjamin performa dan keamanan:

- **Framework:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL / MariaDB
- **Frontend UI:** Bootstrap 5, Vanilla JavaScript, CSS3
- **Payment Gateway:** Midtrans (Snap API)
- **Notification API:** Fonnte (WhatsApp Gateway) & Laravel Mail SMTP
- **AI / Chatbot:** Vanilla JS Rule-Based NLP Engine
- **Export Data:** Maatwebsite Excel, DomPDF

---

## 🚀 Panduan Instalasi (Development)

Ikuti langkah-langkah berikut untuk menjalankan *project* ini di mesin lokal Anda (Windows/Mac/Linux):

### Persyaratan Sistem
- PHP >= 8.2
- Composer 2.x
- Node.js & NPM
- MySQL Server

### Langkah-langkah
1. **Clone Repositori**
   ```bash
   git clone <url-repo-ini>
   cd eticket-skripsi
   ```

2. **Instalasi Dependencies (Backend & Frontend)**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Lingkungan (Environment)**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Buka file `.env` dan atur koneksi `DB_*`, Kredensial `MIDTRANS_*`, serta token `FONNTE_TOKEN` sesuai dengan akun Anda.*

4. **Migrasi Database & Seeding Dummy Data**
   ```bash
   php artisan migrate --seed
   ```

5. **Kompilasi Aset Frontend**
   ```bash
   npm run build
   # atau `npm run dev` untuk pengembangan aktif
   ```

6. **Jalankan Aplikasi Lokal**
   ```bash
   php artisan serve
   ```
   Aplikasi kini dapat diakses melalui `http://localhost:8000`.

---

## 🔑 Akun Uji Coba (Dummy)

Gunakan kredensial berikut untuk melakukan pengujian sistem:

| Role | Email / Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin Sistem** | `admin` | `admin123` | Akses penuh ke seluruh konfigurasi |
| **Kadis Provinsi** | `kadis` | `kadis123` | Melihat seluruh laporan se-Provinsi |
| **Kadis Kab/Kota** | `kadiskotabaru` | `kadis123` | Laporan scoping khusus wilayahnya |
| **Kasir Loket** | `kasirkotabaru` | `kasir123` | Modul penjualan offline (POS) |
| **Petugas Pintu** | `petugas` | `petugas123` | Validasi (Scan QR) Tiket |
| **Pengunjung** | `irwan@mail.com` | `irwan123` | User terdaftar untuk melihat riwayat tiket |

---

## 📜 Lisensi & Atribusi

Proyek ini dikembangkan secara independen sebagai Tugas Akhir/Skripsi. Seluruh elemen sistem, logo (kecuali aset *open-source*), dan konsep bisnis dilindungi hak cipta untuk kebutuhan akademis.

<div align="center">
  Dibuat dengan ❤️ untuk Pariwisata Kalimantan Selatan.
</div>
