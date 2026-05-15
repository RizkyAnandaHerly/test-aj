@extends('layouts.sidebar')
@section('title', 'Detail Barang')
@section('content')


    {{-- Back button --}}
    <div class="mb-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary fw-semibold">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
        </a>
    </div>

    <div class="row g-4">

        {{-- ── Kartu Utama ──────────────────────────────────────────────────── --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom px-4 pt-4 pb-3 d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $product->name }}</h4>
                        <code class="text-secondary fs-6">{{ $product->sku }}</code>
                    </div>
                    <div>
                        @if(strtolower($product->status) === 'active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fs-6">
                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fs-6">
                                <i class="bi bi-x-circle-fill me-1"></i>Non-Aktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <div class="row g-3">

                        <div class="col-6 col-md-4">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Kategori</div>
                            <div class="fw-semibold text-dark">{{ $product->category ?: '—' }}</div>
                        </div>

                        <div class="col-6 col-md-4">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Satuan</div>
                            <div class="fw-semibold text-dark">{{ $product->unit ?: '—' }}</div>
                        </div>

                        <div class="col-6 col-md-4">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Tanggal Ditambahkan</div>
                            <div class="fw-semibold text-dark">
                                {{ $product->created_at->translatedFormat('d M Y') }}
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-1">
                        </div>

                        <div class="col-12">
                            <div class="text-muted small text-uppercase fw-semibold mb-2">Deskripsi</div>
                            <p class="text-dark mb-0">
                                {{ $product->description ?: 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ── Kartu Stok ───────────────────────────────────────────────────── --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom px-4 pt-4 pb-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Informasi Stok
                    </h6>
                </div>
                <div class="card-body px-4 py-4">

                    {{-- Stok saat ini --}}
                    <div class="text-center mb-4">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Stok Sistem Saat Ini</div>
                        <div class="display-5 fw-bold {{ $product->isLowStock() ? 'text-danger' : 'text-success' }}">
                            {{ $product->stock_qty }}
                        </div>
                        <div class="text-muted small">{{ $product->unit }}</div>
                    </div>

                    {{-- Low stock warning --}}
                    @if($product->isLowStock())
                        <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center gap-2 py-2 px-3 mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning flex-shrink-0"></i>
                            <span class="small fw-semibold">
                                Stok di bawah atau sama dengan batas minimum!
                            </span>
                        </div>
                    @endif

                    <hr>

                    {{-- Min Stok --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Batas Minimum Stok</span>
                        <span class="fw-bold text-dark">{{ $product->min_stock }} {{ $product->unit }}</span>
                    </div>

                    {{-- Stok Bar --}}
                    @php
                        $pct = $product->min_stock > 0
                            ? min(100, round(($product->stock_qty / max($product->min_stock, 1)) * 100))
                            : 100;
                        $barClass = $product->isLowStock() ? 'bg-danger' : 'bg-success';
                    @endphp
                    <div class="progress mt-3 rounded-pill" style="height:10px" title="Stok: {{ $pct }}% dari minimum">
                        <div class="progress-bar {{ $barClass }} rounded-pill"
                             role="progressbar"
                             style="width: {{ $pct }}%"
                             aria-valuenow="{{ $pct }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                    <div class="text-muted small mt-1 text-end">{{ $pct }}% dari batas minimum</div>

                </div>
            </div>

            {{-- ── Meta card ────────────────────────────────────────────────── --}}
            <div class="card border-0 shadow-sm rounded-4 mt-3">
                <div class="card-body px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Dibuat</span>
                        <span class="small fw-semibold text-dark">
                            {{ $product->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Terakhir diperbarui</span>
                        <span class="small fw-semibold text-dark">
                            {{ $product->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
