@extends('layouts.app')
@section('title', 'Transaksi Kasir')

@section('content')

{{-- Embed tier diskon dari controller ke JS --}}
<script>
    const DISKON_TIERS = @json($diskonTiers);
</script>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="text-white mb-0"><i class="ti ti-ticket"></i> Kasir Tiket</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.store') }}" method="POST" id="form-transaksi">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Objek Wisata</label>
                        <select name="id_objek" id="id_objek" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($objekWisatas as $ow)
                                <option value="{{ $ow->id }}">{{ $ow->nama_objek }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    <div id="loading" class="text-center py-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Mengambil data tiket...</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-tiket" style="display:none;">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Tiket</th>
                                    <th width="20%">Harga</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="25%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="tiket-container"></tbody>
                        </table>
                    </div>

                    <div id="empty-state" class="text-center py-5 text-muted">
                        <i class="ti ti-map-pin fs-1"></i>
                        <p>Silakan pilih Objek Wisata terlebih dahulu.</p>
                    </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5>Rincian Pembayaran</h5></div>
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between">
                    <span>Tanggal:</span>
                    <strong>{{ date('d-m-Y') }}</strong>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span>Kasir:</span>
                    <strong>{{ Auth::user()->nama }}</strong>
                </div>

                <hr>

                {{-- Info diskon rombongan --}}
                @if($diskonTiers->count() > 0)
                <div id="info-diskon-tiers" class="mb-3 p-3 rounded"
                     style="background:#f0fdf4; border:1px solid #bbf7d0; font-size:12.5px;">
                    <div class="fw-bold mb-1" style="color:#15803d;">
                        <i class="ti ti-discount me-1"></i> Diskon Rombongan Tersedia
                    </div>
                    @foreach($diskonTiers as $tier)
                    <div style="color:#166534;">
                        ≥ {{ $tier->min_orang }} orang →
                        <strong>{{ number_format($tier->persen_diskon, 0) }}% off</strong>
                        @if($tier->keterangan) <span class="text-muted">({{ $tier->keterangan }})</span> @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="mb-2 d-flex justify-content-between text-muted" id="row-subtotal" style="display:none!important;">
                    <span>Subtotal:</span>
                    <span id="display-subtotal">Rp 0</span>
                </div>

                {{-- Badge diskon aktif --}}
                <div id="row-diskon" class="mb-2 p-2 rounded d-flex justify-content-between align-items-center"
                     style="display:none!important; background:#d1fae5; border:1px solid #6ee7b7;">
                    <span style="color:#065f46; font-size:13px; font-weight:600;">
                        <i class="ti ti-discount me-1"></i>
                        Diskon Rombongan (<span id="diskon-persen-label">0</span>%)
                    </span>
                    <span style="color:#065f46; font-weight:700;" id="display-diskon">- Rp 0</span>
                </div>

                <div class="mb-3 text-center p-3 bg-light-primary rounded">
                    <h6 class="text-primary">TOTAL TAGIHAN</h6>
                    <h2 class="mb-0 fw-bold text-primary" id="display-total">Rp 0</h2>
                </div>

                {{-- Hidden inputs untuk dikirim ke server --}}
                <input type="hidden" name="diskon_persen" id="input-diskon-persen" value="0">
                <input type="hidden" name="diskon_nominal" id="input-diskon-nominal" value="0">

                <div class="mb-3">
                    <label class="form-label">Uang Bayar (Rp)</label>
                    <input type="number" name="bayar" id="bayar"
                           class="form-control form-control-lg" placeholder="0" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kembali</label>
                    <input type="text" id="kembali" class="form-control bg-light" readonly value="Rp 0">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"
                        id="btn-submit" disabled>
                    <i class="ti ti-printer"></i> PROSES & CETAK
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const selectObjek    = document.getElementById('id_objek');
const tiketContainer = document.getElementById('tiket-container');
const tabelTiket     = document.getElementById('tabel-tiket');
const emptyState     = document.getElementById('empty-state');
const loading        = document.getElementById('loading');
const displayTotal   = document.getElementById('display-total');
const displaySubtotal= document.getElementById('display-subtotal');
const displayDiskon  = document.getElementById('display-diskon');
const rowSubtotal    = document.getElementById('row-subtotal');
const rowDiskon      = document.getElementById('row-diskon');
const diskonPersLabel= document.getElementById('diskon-persen-label');
const inputDiskonPers= document.getElementById('input-diskon-persen');
const inputDiskonNom = document.getElementById('input-diskon-nominal');
const inputBayar     = document.getElementById('bayar');
const inputKembali   = document.getElementById('kembali');
const btnSubmit      = document.getElementById('btn-submit');

let grandTotal = 0;

function formatRp(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

// Cari tier diskon tertinggi yang berlaku
function cariDiskon(totalQty) {
    let best = null;
    DISKON_TIERS.forEach(function(tier) {
        if (totalQty >= tier.min_orang) {
            if (!best || tier.min_orang > best.min_orang) {
                best = tier;
            }
        }
    });
    return best;
}

selectObjek.addEventListener('change', function() {
    const idObjek = this.value;
    tiketContainer.innerHTML = '';
    grandTotal = 0;
    updateSummary();

    if (!idObjek) {
        tabelTiket.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    emptyState.style.display = 'none';
    loading.style.display = 'block';
    tabelTiket.style.display = 'none';

    fetch(`/get-tiket/${idObjek}`)
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.length === 0) {
                alert('Belum ada setting harga tiket untuk wisata ini!');
                return;
            }
            tabelTiket.style.display = 'table';
            data.forEach(function(item) {
                const row = `
                    <tr>
                        <td>
                            <strong>${item.jenis_tiket.nama_jenis}</strong>
                            <input type="hidden" name="id_jenis_tiket[]" value="${item.jenis_tiket.id}">
                            <input type="hidden" name="harga_satuan[]" value="${item.harga}">
                        </td>
                        <td>${formatRp(item.harga)}</td>
                        <td>
                            <input type="number" name="jumlah[]"
                                   class="form-control input-qty" min="0" value="0"
                                   data-price="${item.harga}"
                                   onchange="hitungSubtotal(this)">
                        </td>
                        <td>
                            <span class="subtotal-text">Rp 0</span>
                            <input type="hidden" name="subtotal[]" class="input-subtotal" value="0">
                        </td>
                    </tr>`;
                tiketContainer.innerHTML += row;
            });
        })
        .catch(() => {
            loading.style.display = 'none';
            alert('Gagal mengambil data tiket.');
        });
});

window.hitungSubtotal = function(el) {
    const qty      = parseInt(el.value) || 0;
    const price    = parseFloat(el.getAttribute('data-price'));
    const subtotal = qty * price;
    const row = el.closest('tr');
    row.querySelector('.subtotal-text').innerText = formatRp(subtotal);
    row.querySelector('.input-subtotal').value    = subtotal;
    hitungGrandTotal();
};

function hitungGrandTotal() {
    let subtotalMentah = 0;
    let totalQty = 0;

    document.querySelectorAll('.input-subtotal').forEach(i => {
        subtotalMentah += parseFloat(i.value) || 0;
    });
    document.querySelectorAll('.input-qty').forEach(i => {
        totalQty += parseInt(i.value) || 0;
    });

    // Cari diskon
    const tierAktif   = cariDiskon(totalQty);
    const persen      = tierAktif ? parseFloat(tierAktif.persen_diskon) : 0;
    const nominalDisk = Math.round(subtotalMentah * persen / 100);
    const totalAkhir  = subtotalMentah - nominalDisk;

    grandTotal = totalAkhir;

    // Update hidden inputs
    inputDiskonPers.value = persen;
    inputDiskonNom.value  = nominalDisk;

    // Tampilkan/sembunyikan baris diskon
    if (persen > 0) {
        rowSubtotal.style.display = 'flex';
        rowDiskon.style.display   = 'flex';
        displaySubtotal.innerText = formatRp(subtotalMentah);
        diskonPersLabel.innerText = persen;
        displayDiskon.innerText   = '- ' + formatRp(nominalDisk);
    } else {
        rowSubtotal.style.display = 'none';
        rowDiskon.style.display   = 'none';
    }

    displayTotal.innerText = formatRp(totalAkhir);
    hitungKembalian();
}

inputBayar.addEventListener('keyup', hitungKembalian);

function hitungKembalian() {
    const bayar    = parseFloat(inputBayar.value) || 0;
    const kembali  = bayar - grandTotal;
    if (kembali >= 0) {
        inputKembali.value = formatRp(kembali);
        inputKembali.classList.remove('text-danger');
        inputKembali.classList.add('text-success');
    } else {
        inputKembali.value = '- ' + formatRp(Math.abs(kembali));
        inputKembali.classList.add('text-danger');
        inputKembali.classList.remove('text-success');
    }
    updateButtonState(bayar);
}

function updateSummary() {
    displayTotal.innerText   = 'Rp 0';
    displaySubtotal.innerText= 'Rp 0';
    inputBayar.value         = '';
    inputKembali.value       = 'Rp 0';
    rowSubtotal.style.display= 'none';
    rowDiskon.style.display  = 'none';
    inputDiskonPers.value    = 0;
    inputDiskonNom.value     = 0;
    btnSubmit.disabled       = true;
}

function updateButtonState(bayar) {
    btnSubmit.disabled = !(grandTotal > 0 && bayar >= grandTotal);
}
</script>
@endsection