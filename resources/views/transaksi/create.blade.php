@extends('layouts.app')
@section('title', 'Transaksi Kasir')

@section('content')
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

                    <div id="loading" class="text-center py-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Mengambil data tiket...</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-tiket" style="display: none;">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Tiket</th>
                                    <th width="20%">Harga</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="25%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="tiket-container">
                                </tbody>
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
                    <strong>{{ Auth::user()->name }}</strong>
                </div>

                <hr>

                <div class="mb-3 text-center p-3 bg-light-primary rounded">
                    <h6 class="text-primary">TOTAL TAGIHAN</h6>
                    <h2 class="mb-0 fw-bold text-primary" id="display-total">Rp 0</h2>
                </div>

                <div class="mb-3">
                    <label class="form-label">Uang Bayar (Rp)</label>
                    <input type="number" name="bayar" id="bayar" class="form-control form-control-lg" placeholder="0" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kembali</label>
                    <input type="text" id="kembali" class="form-control bg-light" readonly value="Rp 0">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="btn-submit" disabled>
                    <i class="ti ti-printer"></i> PROSES & CETAK
                </button>
                </form> </div>
        </div>
    </div>
</div>

<script>
    const selectObjek = document.getElementById('id_objek');
    const tiketContainer = document.getElementById('tiket-container');
    const tabelTiket = document.getElementById('tabel-tiket');
    const emptyState = document.getElementById('empty-state');
    const loading = document.getElementById('loading');
    
    const displayTotal = document.getElementById('display-total');
    const inputBayar = document.getElementById('bayar');
    const inputKembali = document.getElementById('kembali');
    const btnSubmit = document.getElementById('btn-submit');

    let grandTotal = 0;

    // 1. Saat Objek Wisata Dipilih
    selectObjek.addEventListener('change', function() {
        const idObjek = this.value;
        
        // Reset tampilan
        tiketContainer.innerHTML = '';
        grandTotal = 0;
        updateSummary();
        
        if(!idObjek) {
            tabelTiket.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }

        // Tampilkan loading
        emptyState.style.display = 'none';
        loading.style.display = 'block';
        tabelTiket.style.display = 'none';

        // Fetch Data Tiket dari Server
        fetch(`/get-tiket/${idObjek}`)
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                
                if(data.length === 0) {
                    alert('Belum ada setting harga tiket untuk wisata ini!');
                    return;
                }

                tabelTiket.style.display = 'table';

                // Loop data tiket dan buat baris tabel
                data.forEach((item, index) => {
                    const row = `
                        <tr>
                            <td>
                                <strong>${item.jenis_tiket.nama_jenis}</strong>
                                <input type="hidden" name="id_jenis_tiket[]" value="${item.jenis_tiket.id}">
                                <input type="hidden" name="harga_satuan[]" value="${item.harga}">
                            </td>
                            <td>
                                Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}
                            </td>
                            <td>
                                <input type="number" name="jumlah[]" class="form-control input-qty" min="0" value="0" data-price="${item.harga}" onchange="hitungSubtotal(this)">
                            </td>
                            <td>
                                <span class="subtotal-text">Rp 0</span>
                                <input type="hidden" name="subtotal[]" class="input-subtotal" value="0">
                            </td>
                        </tr>
                    `;
                    tiketContainer.innerHTML += row;
                });
            })
            .catch(err => {
                console.error(err);
                loading.style.display = 'none';
                alert('Gagal mengambil data tiket.');
            });
    });

    // 2. Fungsi Hitung Subtotal per Baris
    window.hitungSubtotal = function(element) {
        const qty = parseInt(element.value) || 0;
        const price = parseFloat(element.getAttribute('data-price'));
        const subtotal = qty * price;

        // Cari elemen saudaranya untuk update tampilan
        const row = element.closest('tr');
        row.querySelector('.subtotal-text').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        row.querySelector('.input-subtotal').value = subtotal;

        hitungGrandTotal();
    }

    // 3. Fungsi Hitung Total Keseluruhan
    function hitungGrandTotal() {
        let total = 0;
        const subtotalInputs = document.querySelectorAll('.input-subtotal');
        
        subtotalInputs.forEach(input => {
            total += parseFloat(input.value);
        });

        grandTotal = total;
        displayTotal.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        
        hitungKembalian();
    }

    // 4. Hitung Kembalian saat ketik uang bayar
    inputBayar.addEventListener('keyup', hitungKembalian);

    function hitungKembalian() {
        const bayar = parseFloat(inputBayar.value) || 0;
        const kembali = bayar - grandTotal;

        if (kembali >= 0) {
            inputKembali.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembali);
            inputKembali.classList.remove('text-danger');
            inputKembali.classList.add('text-success');
        } else {
            inputKembali.value = '- Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(kembali));
            inputKembali.classList.add('text-danger');
        }

        updateButtonState(bayar);
    }

    function updateSummary() {
        displayTotal.innerText = 'Rp 0';
        inputBayar.value = '';
        inputKembali.value = 'Rp 0';
        btnSubmit.disabled = true;
    }

    // 5. Validasi Tombol Submit
    function updateButtonState(bayar) {
        if (grandTotal > 0 && bayar >= grandTotal) {
            btnSubmit.disabled = false;
        } else {
            btnSubmit.disabled = true;
        }
    }
</script>
@endsection