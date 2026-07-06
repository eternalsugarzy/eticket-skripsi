<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Master - {{ $judul }}</title>
    <style>
        @page { size: A4 portrait; margin: 15mm 20mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; margin: 0; }

        /* KOP */
        .kop-table { width: 100%; border-collapse: collapse; border: none; margin-bottom: 5px; }
        .kop-table td { border: none; vertical-align: middle; }
        .kop-logo-cell { width: 15%; text-align: center; }
        .logo-img { width: 90px; height: auto; }
        .kop-text-cell { width: 85%; text-align: center; padding-right: 15px; }
        .text-col p { margin: 0; line-height: 1.25; color: #000; }
        .line1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .line2 { font-size: 18pt; font-weight: bold; text-transform: uppercase; }
        .line-contact-utama { font-size: 10pt; line-height: 1.2; margin-top: 5px; }
        .hr-separator { border: 0; border-top: 3px double black; height: 3px; margin-bottom: 20px; }

        /* JUDUL */
        h1 { text-align: center; font-size: 15px; margin-bottom: 6px; text-decoration: underline;
             font-family: Arial, sans-serif; text-transform: uppercase; }
        .sub-judul { text-align: center; font-family: Arial, sans-serif; font-size: 11px;
                     color: #444; margin-bottom: 18px; }

        /* TABEL */
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px;
                           font-family: Arial, sans-serif; font-size: 11px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
        table.data-table th { background-color: #e0e0e0; text-align: center; font-weight: bold; }
        .total-row td { font-weight: bold; background-color: #f2f2f2; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .badge-buka   { background:#d4edda; padding:2px 6px; border-radius:3px; font-size:10px; }
        .badge-tutup  { background:#f8d7da; padding:2px 6px; border-radius:3px; font-size:10px; }
        .badge-role   { background:#d1ecf1; padding:2px 6px; border-radius:3px; font-size:10px; }

        /* TTD */
        .tanda-tangan { margin-top: 40px; width: 100%; font-family: Arial, sans-serif; page-break-inside: avoid; }
        .ttd-wrapper  { display: table; width: 100%; }
        .ttd-kiri, .ttd-kanan { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
        .ttd-spacer   { height: 70px; }
    </style>
</head>
<body onload="window.print()">

    {{-- KOP SURAT --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                <img class="logo-img" src="{{ asset('assets/images/logo2.png') }}" alt="Logo Kalsel">
            </td>
            <td class="kop-text-cell text-col">
                <p class="line1">PEMERINTAH PROVINSI KALIMANTAN SELATAN</p>
                <p class="line2">DINAS PARIWISATA</p>
                <p class="line-contact-utama">Jalan Jenderal Ahmad Yani KM 7,5 Kertak Hanyar, Kab. Banjar 70654</p>
                <p class="line-contact-utama">Telepon: (0511) 6795599 &nbsp;|&nbsp; Laman: dispar.kalselprov.go.id</p>
                <p class="line-contact-utama">Pos-el: disparprovkalsel@gmail.com</p>
            </td>
        </tr>
    </table>
    <hr class="hr-separator">

    <h1>{{ $judul }}</h1>
    <p class="sub-judul">Dicetak pada: {{ date('d F Y, H:i') }} WITA &nbsp;|&nbsp; Total Data: {{ count($data) }} record</p>

    {{-- ============ TABEL USERS ============ --}}
    @if($jenis == 'users')
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th width="15%">Role / Jabatan</th>
                <th width="20%">Tgl Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->username }}</td>
                <td class="text-center">
                    <span class="badge-role">{{ ucfirst($row->role) }}</span>
                </td>
                <td class="text-center">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Total Pengguna</td>
                <td class="text-center">{{ count($data) }} Akun</td>
            </tr>
        </tfoot>
    </table>

    {{-- ============ TABEL KABUPATENS ============ --}}
    @elseif($jenis == 'kabupatens')
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Kabupaten / Kota</th>
                <th width="25%">Tgl Ditambahkan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row->nama_kabupaten }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-right">Total Kabupaten / Kota</td>
                <td class="text-center">{{ count($data) }} Wilayah</td>
            </tr>
        </tfoot>
    </table>

    {{-- ============ TABEL OBJEK WISATA ============ --}}
    @elseif($jenis == 'objek_wisatas')
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Objek Wisata</th>
                <th width="20%">Kabupaten / Kota</th>
                <th>Alamat</th>
                <th width="15%">Jam Operasional</th>
                <th width="8%">Status</th>
                <th width="8%">Populer</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row->nama_objek }}</td>
                <td>{{ $row->nama_kabupaten }}</td>
                <td>{{ $row->alamat ?? '-' }}</td>
                <td class="text-center">{{ $row->jam_operasional ?? '-' }}</td>
                <td class="text-center">
                    @if($row->status == 'buka')
                        <span class="badge-buka">Buka</span>
                    @else
                        <span class="badge-tutup">Tutup</span>
                    @endif
                </td>
                <td class="text-center">{{ $row->is_populer ? '⭐ Ya' : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-right">Total Objek Wisata</td>
                <td class="text-center">{{ count($data) }} Lokasi</td>
            </tr>
        </tfoot>
    </table>

    {{-- ============ TABEL JENIS TIKET ============ --}}
    @elseif($jenis == 'jenis_tikets')
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Jenis Tiket</th>
                <th width="25%">Tgl Ditambahkan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row->nama_jenis }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-right">Total Jenis Tiket</td>
                <td class="text-center">{{ count($data) }} Jenis</td>
            </tr>
        </tfoot>
    </table>

    {{-- ============ TABEL HARGA TIKET ============ --}}
    @elseif($jenis == 'harga_tikets')
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Objek Wisata</th>
                <th width="20%">Jenis Tiket</th>
                <th width="22%" class="text-right">Harga (IDR)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row->nama_objek }}</td>
                <td>{{ $row->nama_jenis }}</td>
                <td class="text-right">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Record Harga</td>
                <td class="text-right">{{ count($data) }} Data</td>
            </tr>
        </tfoot>
    </table>

    {{-- ============ TABEL BERITA ============ --}}
    @elseif($jenis == 'beritas')
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Judul Berita</th>
                <th width="14%">Kategori</th>
                <th width="16%">Wilayah</th>
                <th width="13%">Tgl Publish</th>
                <th width="14%">Penulis</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row->judul }}</td>
                <td class="text-center">{{ $row->kategori }}</td>
                <td>{{ $row->nama_kabupaten ?? 'Provinsi (Umum)' }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($row->tanggal_publish)) }}</td>
                <td>{{ $row->nama_penulis ?? '-' }}</td>
                <td class="text-center">
                    @if($row->status == 'published')
                        <span class="badge-buka">Published</span>
                    @else
                        <span class="badge-tutup">Draft</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-right">Total Berita</td>
                <td class="text-center">{{ count($data) }} Artikel</td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- TANDA TANGAN --}}
    <div class="tanda-tangan">
        <div class="ttd-wrapper">
            <div class="ttd-kiri">
                <p>Mengetahui,</p>
                <p>Pejabat Dinas Terkait</p>
                <div class="ttd-spacer"></div>
                <p><strong>(Nama Pejabat)</strong></p>
                <p>NIP. .....................</p>
            </div>
            <div class="ttd-kanan">
                <p>Banjarmasin, {{ date('d F Y') }}</p>
                <p>Kepala Dinas</p>
                <div class="ttd-spacer"></div>
                <p><strong>IWAN FITRIADI, SH.,MH</strong></p>
                <p>NIP 19612251998031004</p>
            </div>
        </div>
    </div>

</body>
</html>