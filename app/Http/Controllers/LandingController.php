<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\Kabupaten;
use App\Models\Berita;
use App\Models\Banner;
use App\Models\Event;
use App\Models\VideoTerbaru;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    // 1. Halaman Utama
    public function index()
    {
        // Semua data homepage adalah konten bersama (tidak bergantung user) yang jarang
        // berubah, jadi di-cache 10 menit dalam satu key supaya tidak query DB tiap kunjungan.
        // Konten baru dari admin muncul maksimal ~10 menit kemudian.
        $data = Cache::remember('landing_home_bundle', now()->addMinutes(10), function () {
            return [
                'allWisata' => ObjekWisata::with('kabupaten')->take(6)->get(),

                'wisataMarkers' => ObjekWisata::whereNotNull('latitude')
                                    ->whereNotNull('longitude')
                                    ->where('latitude', '!=', '')
                                    ->where('longitude', '!=', '')
                                    ->get(),

                'beritaTerbaru' => Berita::published()->orderByDesc('tanggal_publish')->take(3)->get(),

                'banners' => Banner::aktifSaatIni()->orderBy('urutan')->get(),

                'eventTerbaru' => Event::aktif()->with('objekWisata')->orderByDesc('tanggal_event')->take(5)->get(),

                'videoTerbaru' => VideoTerbaru::first(),
            ];
        });

        return view('frontend.index', $data);
    }

    // 2. Halaman Katalog (dengan Search & Filter)
    public function katalog(Request $request)
    {
        $query = ObjekWisata::with('kabupaten')->orderBy('nama_objek', 'asc');

        // Filter: pencarian nama objek wisata
        if ($request->filled('q')) {
            $query->where('nama_objek', 'like', '%' . $request->q . '%');
        }

        // Filter: berdasarkan kabupaten
        if ($request->filled('kabupaten')) {
            $query->where('id_kabupaten', $request->kabupaten);
        }

        // Paginate 12 per halaman, pertahankan query string di link pagination
        $allWisata  = $query->paginate(12)->withQueryString();

        // Data untuk dropdown filter kabupaten (daftar 13 wilayah, hampir statis — di-cache)
        $kabupatens = Kabupaten::cached();

        // Daftar ID objek wisata yang sudah di-wishlist pengunjung yang login (kalau ada)
        $pengunjungLogin = auth('pengunjung')->user();
        $wishlistIds = $pengunjungLogin
            ? \App\Models\Wishlist::where('id_pengunjung', $pengunjungLogin->id)->pluck('id_objek')->toArray()
            : [];

        return view('frontend.katalog', compact('allWisata', 'kabupatens', 'wishlistIds'));
    }

    // 3. Halaman Detail
    public function detail($id)
    {
        $wisata     = ObjekWisata::with(['kabupaten', 'galeri'])->findOrFail($id);
        $hargaTiket = \DB::table('harga_tikets')
            ->join('jenis_tikets', 'harga_tikets.id_jenis_tiket', '=', 'jenis_tikets.id')
            ->where('harga_tikets.id_objek', $id)
            ->select('jenis_tikets.nama_jenis', 'harga_tikets.harga')
            ->get();

        $cuaca = $this->ambilCuaca($wisata->latitude, $wisata->longitude);

        // Ulasan & rating
        $ulasans = \App\Models\Ulasan::with('pengunjung')
            ->where('id_objek', $id)
            ->latest()
            ->paginate(5, ['*'], 'halaman_ulasan');

        $pengunjungLogin = auth('pengunjung')->user();
        $bisaUlasan = $pengunjungLogin
            ? \App\Models\Ulasan::bisaUlasan($pengunjungLogin->id, $id)
            : false;

        // Status wishlist untuk pengunjung yang login
        $sudahWishlist = $pengunjungLogin
            ? \App\Models\Wishlist::where('id_pengunjung', $pengunjungLogin->id)->where('id_objek', $id)->exists()
            : false;

        return view('frontend.detail', compact('wisata', 'hargaTiket', 'cuaca', 'ulasans', 'bisaUlasan', 'sudahWishlist'));
    }

    // =========================================================
    // PRIVATE HELPER — Ambil data cuaca dari OpenWeatherMap
    // Di-cache 30 menit per koordinat supaya hemat kuota API
    // =========================================================
    private function ambilCuaca($lat, $lon)
    {
        $apiKey = env('OPENWEATHER_API_KEY');

        if (!$lat || !$lon || !$apiKey) {
            return null; // Widget otomatis tersembunyi kalau key belum diatur
        }

        $cacheKey = 'cuaca_' . round($lat, 2) . '_' . round($lon, 2);

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(30), function () use ($lat, $lon, $apiKey) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://api.openweathermap.org/data/2.5/weather', [
                    'lat'   => $lat,
                    'lon'   => $lon,
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang'  => 'id',
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                // Gagal ambil cuaca (misal API key belum aktif) — biarkan null, widget hilang otomatis
            }

            return null;
        });
    }
}