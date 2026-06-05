@extends('layouts.sidebar')
@section('title', 'Label Packing Barang')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Label Packing Barang</h5>
                        <p class="text-muted small mb-0">Detail fisik packing dan kode label untuk produk.</p>
                    </div>
                    <a href="{{ route('packing-details.index') }}" class="btn btn-sm btn-light border px-3">
                        Kembali ke Daftar
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small text-uppercase">Label Kode</span>
                            <span class="badge bg-primary-subtle text-primary text-uppercase">{{ $packingDetail->label_code }}</span>
                        </div>
                        <div class="mb-3">
                            <h4 class="fw-bold">{{ $packingDetail->product->name ?? 'Produk' }}</h4>
                            <div class="text-muted small">SKU: {{ $packingDetail->product->sku ?? '—' }}</div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-muted small">Qty Packing</div>
                                <div class="fw-bold fs-5">{{ number_format($packingDetail->quantity) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Tipe Packaging</div>
                                <div class="fw-bold fs-5">{{ $packingDetail->packaging_type }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Berat Paket</div>
                                <div class="fw-bold fs-5">{{ $packingDetail->package_weight ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Dimensi Paket</div>
                                <div class="fw-bold">{{ $packingDetail->package_dimensions ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Packer</div>
                                <div class="fw-bold">{{ $packingDetail->packer->name ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="text-muted small">Catatan Fisik</div>
                            <div class="fw-semibold text-dark">{{ $packingDetail->notes ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="javascript:window.print()" class="btn btn-outline-primary px-4 py-2 fw-semibold">
                            <i class="bi bi-printer-fill me-1"></i> Cetak Label
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
