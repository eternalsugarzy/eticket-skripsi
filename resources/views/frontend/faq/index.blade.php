@extends('frontend.layouts.app')

@section('title', 'FAQ - Pertanyaan Umum - E-Tourism Kalsel')

@push('styles')
<style>
:root {
    --forest:     #1A3D2B;
    --forest-mid: #2A5C40;
    --gold:       #C9933A;
    --gold-light: #F5E6C8;
    --cream:      #F7F4EF;
    --text-dark:  #0F1C14;
    --text-muted: #5A6872;
}
body { background: var(--cream); }

.faq-header {
    background: linear-gradient(135deg, var(--forest) 0%, var(--forest-mid) 100%);
    padding: 96px 0 48px;
    text-align: center;
}
.faq-header h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.8rem, 5vw, 2.6rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: 10px;
}
.faq-header p { color: rgba(255,255,255,.65); }
.gold-strip { height:4px; background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest)); }

.faq-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(15,28,20,.06);
    overflow: hidden;
    margin-bottom: 32px;
}
.faq-card-header {
    background: var(--cream);
    padding: 16px 24px;
    border-bottom: 1px solid rgba(26,61,43,.08);
}
.faq-card-header h5 {
    font-weight: 700;
    color: var(--forest);
    margin: 0;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.faq-card-header i { color: var(--gold); font-size: 1.1rem; }

.accordion-button {
    font-weight: 700;
    color: var(--text-dark);
    font-size: .95rem;
}
.accordion-button:not(.collapsed) {
    background: var(--gold-light);
    color: #8a611f;
    box-shadow: none;
}
.accordion-button:focus { box-shadow: none; border-color: rgba(26,61,43,.1); }
.accordion-button::after { flex-shrink: 0; }
.accordion-body {
    color: var(--text-muted);
    font-size: .9rem;
    line-height: 1.75;
}
.accordion-item { border-color: rgba(26,61,43,.08); }

.cta-box {
    background: linear-gradient(135deg, var(--forest) 0%, var(--forest-mid) 100%);
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    color: #fff;
}
.cta-box h5 { font-weight: 700; margin-bottom: 8px; }
.cta-box p { color: rgba(255,255,255,.7); font-size: .9rem; margin-bottom: 20px; }
.btn-cta {
    background: var(--gold);
    color: #fff;
    font-weight: 700;
    padding: 11px 28px;
    border-radius: 50px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background .2s, transform .15s;
}
.btn-cta:hover { background: #b07d28; color: #fff; transform: translateY(-2px); }
</style>
@endpush

@section('content')

<div class="faq-header">
    <div class="container">
        <h1>{{ __('Pertanyaan yang Sering Diajukan') }}</h1>
        <p>{{ __('Temukan jawaban seputar pemesanan tiket wisata Kalimantan Selatan') }}</p>
    </div>
</div>
<div class="gold-strip"></div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ═══════ KATEGORI: PEMESANAN TIKET ═══════ --}}
            <div class="faq-card reveal">
                <div class="faq-card-header">
                    <h5><i class="bi bi-ticket-perforated-fill"></i> {{ __('Pemesanan Tiket') }}</h5>
                </div>
                <div class="accordion accordion-flush" id="accordionPesan">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#p1">
                                {{ __('Bagaimana cara memesan tiket wisata secara online?') }}
                            </button>
                        </h2>
                        <div id="p1" class="accordion-collapse collapse show" data-bs-parent="#accordionPesan">
                            <div class="accordion-body">
                                {!! __('faq_answer_1') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#p2">
                                {{ __('Apakah saya wajib membuat akun untuk memesan tiket?') }}
                            </button>
                        </h2>
                        <div id="p2" class="accordion-collapse collapse" data-bs-parent="#accordionPesan">
                            <div class="accordion-body">
                                {!! __('faq_answer_2') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#p3">
                                {{ __('Apakah ada diskon untuk rombongan/kelompok besar?') }}
                            </button>
                        </h2>
                        <div id="p3" class="accordion-collapse collapse" data-bs-parent="#accordionPesan">
                            <div class="accordion-body">
                                {!! __('faq_answer_3') !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ═══════ KATEGORI: PEMBAYARAN ═══════ --}}
            <div class="faq-card reveal" style="transition-delay:.08s;">
                <div class="faq-card-header">
                    <h5><i class="bi bi-credit-card-fill"></i> {{ __('Pembayaran') }}</h5>
                </div>
                <div class="accordion accordion-flush" id="accordionBayar">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#b1">
                                {{ __('Metode pembayaran apa saja yang tersedia?') }}
                            </button>
                        </h2>
                        <div id="b1" class="accordion-collapse collapse" data-bs-parent="#accordionBayar">
                            <div class="accordion-body">
                                {!! __('faq_answer_4') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#b2">
                                {{ __('Berapa lama batas waktu pembayaran sebelum pesanan dibatalkan?') }}
                            </button>
                        </h2>
                        <div id="b2" class="accordion-collapse collapse" data-bs-parent="#accordionBayar">
                            <div class="accordion-body">
                                {!! __('faq_answer_5') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#b3">
                                {{ __('Apakah pembayaran saya aman?') }}
                            </button>
                        </h2>
                        <div id="b3" class="accordion-collapse collapse" data-bs-parent="#accordionBayar">
                            <div class="accordion-body">
                                {!! __('faq_answer_6') !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ═══════ KATEGORI: E-TICKET & KUNJUNGAN ═══════ --}}
            <div class="faq-card reveal" style="transition-delay:.16s;">
                <div class="faq-card-header">
                    <h5><i class="bi bi-qr-code"></i> {{ __('E-Ticket & Kunjungan') }}</h5>
                </div>
                <div class="accordion accordion-flush" id="accordionTiket">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#e1">
                                {{ __('Bagaimana cara mendapatkan E-Ticket saya?') }}
                            </button>
                        </h2>
                        <div id="e1" class="accordion-collapse collapse" data-bs-parent="#accordionTiket">
                            <div class="accordion-body">
                                {!! __('faq_answer_7') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#e2">
                                {{ __('Saya lupa/kehilangan kode pesanan saya, bagaimana solusinya?') }}
                            </button>
                        </h2>
                        <div id="e2" class="accordion-collapse collapse" data-bs-parent="#accordionTiket">
                            <div class="accordion-body">
                                {!! __('faq_answer_8') !!}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#e3">
                                {{ __('Apakah E-Ticket bisa dipakai berkali-kali?') }}
                            </button>
                        </h2>
                        <div id="e3" class="accordion-collapse collapse" data-bs-parent="#accordionTiket">
                            <div class="accordion-body">
                                {!! __('faq_answer_9') !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- CTA lacak pesanan --}}
            <div class="cta-box reveal">
                <h5>{{ __('Masih Ada Pertanyaan Lain?') }}</h5>
                <p>{{ __('Cek status pesanan Anda kapan saja, atau hubungi kami langsung.') }}</p>
                <a href="{{ route('cek-pesanan') }}" class="btn-cta">
                    <i class="bi bi-search"></i> {{ __('Lacak Pesanan Saya') }}
                </a>
            </div>

        </div>
    </div>
</div>

@endsection