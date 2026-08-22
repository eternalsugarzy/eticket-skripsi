<?php

namespace App\Http\Controllers;

use App\Models\ObjekWisata;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Mengembalikan seluruh knowledge base chatbot dalam format JSON.
     * Berisi: FAQ statis + data wisata dinamis dari database.
     */
    public function knowledge()
    {
        // 1. Data wisata dari database (jam operasional, harga, alamat)
        $wisataData = ObjekWisata::with(['hargaTikets.jenisTiket', 'kabupaten'])
            ->where('status', 'buka')
            ->get()
            ->map(function ($w) {
                return [
                    'nama'      => $w->nama_objek,
                    'kabupaten' => $w->kabupaten->nama_kabupaten ?? '-',
                    'alamat'    => $w->alamat ?? '-',
                    'jam'       => $w->jam_operasional ?? 'Tidak tersedia',
                    'status'    => $w->status,
                    'harga'     => $w->hargaTikets->map(fn($h) => [
                        'jenis' => $h->jenisTiket->nama_jenis ?? 'Umum',
                        'harga' => (int) $h->harga,
                    ])->toArray(),
                ];
            });

        // 2. FAQ statis
        $faqStatis = [
            [
                'keywords' => ['pesan', 'beli', 'cara', 'order', 'tiket', 'booking', 'book', 'how', 'buy', 'ticket'],
                'question_id' => 'Bagaimana cara memesan tiket?',
                'question_en' => 'How to order tickets?',
                'answer_id' => 'Pilih destinasi wisata di halaman Katalog Wisata, klik "Lihat Detail & Tiket", lalu klik "Pesan Tiket Sekarang". Isi data diri, pilih jumlah dan jenis tiket, lalu lanjutkan ke pembayaran. Anda akan mendapatkan kode pesanan untuk melacak status tiket.',
                'answer_en' => 'Select a destination from the Tourism Catalog, click "View Details & Tickets", then click "Order Tickets Now". Fill in your details, choose ticket types and quantities, then proceed to payment. You will receive an order code to track your ticket status.',
            ],
            [
                'keywords' => ['akun', 'daftar', 'registrasi', 'wajib', 'harus', 'account', 'register', 'mandatory', 'required'],
                'question_id' => 'Apakah harus membuat akun?',
                'question_en' => 'Do I need to create an account?',
                'answer_id' => 'Tidak wajib. Anda bisa memesan tiket tanpa akun (cukup isi nama, WhatsApp, dan email saat checkout). Namun dengan akun, riwayat pesanan tersimpan otomatis.',
                'answer_en' => 'No, it\'s not mandatory. You can order tickets without an account (just fill in your name, WhatsApp, and email at checkout). However, with an account, your order history is automatically saved.',
            ],
            [
                'keywords' => ['diskon', 'rombongan', 'grup', 'kelompok', 'group', 'discount', 'promo'],
                'question_id' => 'Apakah ada diskon untuk rombongan?',
                'question_en' => 'Are there group discounts?',
                'answer_id' => 'Ya! Sistem otomatis memberikan diskon rombongan jika jumlah tiket dalam satu transaksi mencapai jumlah minimal tertentu. Diskon langsung terlihat di ringkasan pembayaran.',
                'answer_en' => 'Yes! The system automatically applies group discounts when the number of tickets in a single transaction reaches a certain minimum. The discount is shown in the payment summary.',
            ],
            [
                'keywords' => ['bayar', 'pembayaran', 'metode', 'pay', 'payment', 'method', 'qris', 'gopay', 'ovo', 'dana', 'transfer'],
                'question_id' => 'Metode pembayaran apa saja yang tersedia?',
                'question_en' => 'What payment methods are available?',
                'answer_id' => 'Pembayaran online bisa melalui QRIS, e-wallet (GoPay, OVO, DANA, ShopeePay, LinkAja), m-banking, dan kartu kredit. Untuk pembelian di loket wisata, tersedia pembayaran tunai dan QRIS.',
                'answer_en' => 'Online payments can be made via QRIS, e-wallets (GoPay, OVO, DANA, ShopeePay, LinkAja), mobile banking, and credit cards. For on-site purchases, cash and QRIS are available.',
            ],
            [
                'keywords' => ['batas', 'waktu', 'expired', 'kadaluarsa', 'deadline', 'time', 'limit', 'expire'],
                'question_id' => 'Berapa lama batas waktu pembayaran?',
                'question_en' => 'What is the payment deadline?',
                'answer_id' => 'Pesanan yang belum dibayar tetap berstatus "Belum Bayar" dan bisa diselesaikan kapan saja melalui halaman Cek Pesanan. Kami sarankan menyelesaikan pembayaran secepatnya.',
                'answer_en' => 'Unpaid orders remain with "Unpaid" status and can be completed anytime via the Check Order page. We recommend completing payment as soon as possible.',
            ],
            [
                'keywords' => ['aman', 'keamanan', 'safe', 'secure', 'security', 'aman'],
                'question_id' => 'Apakah pembayaran aman?',
                'question_en' => 'Is the payment secure?',
                'answer_id' => 'Ya, seluruh transaksi diproses melalui sistem pembayaran Midtrans yang terenkripsi dan aman.',
                'answer_en' => 'Yes, all transactions are processed through Midtrans\' encrypted and secure payment system.',
            ],
            [
                'keywords' => ['e-ticket', 'eticket', 'tiket', 'digital', 'qr', 'dapatkan', 'dapat', 'get', 'receive', 'ticket'],
                'question_id' => 'Bagaimana cara mendapatkan E-Ticket?',
                'question_en' => 'How do I get my E-Ticket?',
                'answer_id' => 'Setelah pembayaran berhasil, E-Ticket dengan QR Code otomatis tersedia. Buka halaman "Cek Pesanan", masukkan kode pesanan, lalu klik "Tampilkan E-Ticket". E-Ticket juga dikirim via email dan WhatsApp.',
                'answer_en' => 'After successful payment, an E-Ticket with QR Code is automatically available. Open the "Check Order" page, enter your order code, and click "Show E-Ticket". The E-Ticket is also sent via email and WhatsApp.',
            ],
            [
                'keywords' => ['lupa', 'hilang', 'kode', 'pesanan', 'forgot', 'lost', 'code', 'order'],
                'question_id' => 'Saya lupa kode pesanan, bagaimana?',
                'question_en' => 'I forgot my order code, what should I do?',
                'answer_id' => 'Kode pesanan dikirimkan ke email yang Anda daftarkan saat checkout. Jika punya akun, Anda bisa melihat semua riwayat pesanan melalui menu "Riwayat Pesanan" setelah login.',
                'answer_en' => 'The order code was sent to the email you registered during checkout. If you have an account, you can view all order history in the "Order History" menu after logging in.',
            ],
            [
                'keywords' => ['pakai', 'berkali', 'kali', 'ulang', 'reuse', 'multiple', 'times', 'use', 'again'],
                'question_id' => 'Apakah E-Ticket bisa dipakai berkali-kali?',
                'question_en' => 'Can the E-Ticket be used multiple times?',
                'answer_id' => 'Tidak. QR Code pada E-Ticket hanya berlaku satu kali validasi masuk (sekali scan oleh petugas). Pastikan E-Ticket siap saat tiba di lokasi.',
                'answer_en' => 'No. The QR Code on the E-Ticket is valid for one-time entry validation only (scanned once by the officer). Make sure your E-Ticket is ready when you arrive.',
            ],
            [
                'keywords' => ['peta', 'lokasi', 'map', 'rute', 'jalan', 'arah', 'direction', 'route', 'location', 'where', 'dimana', 'mana'],
                'question_id' => 'Bagaimana melihat lokasi wisata di peta?',
                'question_en' => 'How to see tourist locations on the map?',
                'answer_id' => 'Anda bisa melihat persebaran seluruh objek wisata di peta interaktif pada halaman utama (bagian Peta SIG). Klik marker untuk melihat detail lokasi. Di halaman detail setiap wisata juga tersedia peta mini.',
                'answer_en' => 'You can see the distribution of all tourist attractions on the interactive map on the main page (GIS Map section). Click markers for location details. A mini map is also available on each attraction\'s detail page.',
            ],
            [
                'keywords' => ['voucher', 'kupon', 'kode', 'promo', 'coupon', 'code'],
                'question_id' => 'Bagaimana cara menggunakan kode voucher?',
                'question_en' => 'How to use a voucher code?',
                'answer_id' => 'Saat checkout, masukkan kode voucher di kolom yang tersedia dan klik "Gunakan". Jika valid, diskon akan otomatis diterapkan pada total pembayaran Anda.',
                'answer_en' => 'During checkout, enter the voucher code in the provided field and click "Apply". If valid, the discount will be automatically applied to your total payment.',
            ],
            [
                'keywords' => ['kontak', 'hubungi', 'telepon', 'telp', 'contact', 'call', 'phone', 'email', 'whatsapp', 'wa'],
                'question_id' => 'Bagaimana cara menghubungi customer service?',
                'question_en' => 'How to contact customer service?',
                'answer_id' => 'Anda dapat menghubungi Dinas Pariwisata Provinsi Kalimantan Selatan melalui halaman kontak di website kami atau datang langsung ke loket wisata terdekat.',
                'answer_en' => 'You can contact the South Kalimantan Provincial Tourism Office through the contact page on our website or visit the nearest tourism counter.',
            ],
        ];

        return response()->json([
            'wisata' => $wisataData,
            'faq'    => $faqStatis,
        ]);
    }
}
