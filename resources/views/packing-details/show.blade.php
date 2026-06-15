@extends('layouts.sidebar')
@section('title', 'Label Packing Barang')

@section('styles')
<style>
    /* Styling khusus cetak label */
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-label, #printable-label * {
            visibility: visible;
        }
        #printable-label {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
    }
    .border-start-md {
        border-left: 1px solid #dee2e6;
    }
    @media (max-width: 767.98px) {
        .border-start-md {
            border-left: none;
            border-top: 1px solid #dee2e6;
            padding-top: 1.5rem;
        }
    }
</style>
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 no-print" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4" id="printable-label">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between no-print">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Label Packing Barang</h5>
                        <p class="text-muted small mb-0">Detail fisik packing dan kode label untuk produk.</p>
                    </div>
                    <a href="{{ route('packing-details.index') }}" class="btn btn-sm btn-light border px-3">
                        Kembali ke Daftar
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="bg-light rounded-4 p-4 mb-4 border border-secondary-subtle">
                        <div class="row g-4 align-items-center">
                            
                            {{-- Info Barang --}}
                            <div class="col-md-8 no-print">
                                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                    <span class="text-muted small text-uppercase fw-bold">Traceability Label</span>
                                    <span class="badge bg-primary text-uppercase px-3 py-2 rounded-pill">{{ $packingDetail->label_code }}</span>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="fw-bold text-dark mb-1">{{ $packingDetail->product->name ?? 'Produk' }}</h4>
                                    <div class="text-muted small">SKU: <span class="fw-semibold text-dark">{{ $packingDetail->product->sku ?? '—' }}</span></div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-6 col-sm-4">
                                        <div class="text-muted small text-uppercase">Qty Packing</div>
                                        <div class="fw-bold text-dark fs-5">{{ number_format($packingDetail->quantity) }} {{ $packingDetail->product->unit ?? 'pcs' }}</div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="text-muted small text-uppercase">Tipe Packaging</div>
                                        <div class="fw-bold text-dark fs-5">{{ $packingDetail->packaging_type }}</div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="text-muted small text-uppercase">Berat Paket</div>
                                        <div class="fw-bold text-dark fs-5">{{ $packingDetail->package_weight ?? '-' }}</div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="text-muted small text-uppercase">Dimensi Paket</div>
                                        <div class="fw-semibold text-dark">{{ $packingDetail->package_dimensions ?? '-' }}</div>
                                    </div>
                                    <div class="col-6 col-sm-8">
                                        <div class="text-muted small text-uppercase">Packer</div>
                                        <div class="fw-semibold text-dark">{{ $packingDetail->packer->name ?? '—' }}</div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top">
                                    <div class="text-muted small text-uppercase">Catatan Fisik</div>
                                    <div class="fw-semibold text-secondary small">{{ $packingDetail->notes ?? 'Tidak ada catatan.' }}</div>
                                </div>
                            </div>

                            {{-- QR Code (Traceability Link) --}}
                            <div class="col-md-4 text-center ps-md-4">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="bg-white p-3 rounded-4 shadow-sm border mb-2 d-inline-block print-area">
                                        <div id="qrcode-container"></div>
                                    </div>
                                    <div class="text-muted small fw-bold text-uppercase mt-2">{{ $packingDetail->label_code }}</div>
                                    <div class="text-primary-emphasis small mt-1 d-none d-md-block" style="font-size: 0.75rem;">
                                        <i class="bi bi-qr-code-scan me-1"></i> Scan untuk Lacak
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-center no-print">
                        <button onclick="window.print()" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-printer-fill me-1"></i> Cetak Label
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan container QR code benar-benar ada sebelum membuat QR
        const qrContainer = document.getElementById("qrcode-container");
        
        if (qrContainer) {
            const trackingUrl = "{{ route('track', ['search' => $packingDetail->label_code]) }}";
            
            // Bersihkan container dulu (berjaga-jaga jika ter-render dua kali)
            qrContainer.innerHTML = '';
            
            new QRCode(qrContainer, {
                text: trackingUrl,
                width: 140,
                height: 140,
                colorDark: "#1e293b",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        } else {
            console.error("Elemen dengan ID 'qrcode-container' tidak ditemukan.");
        }
    });
</script>
@endsection
