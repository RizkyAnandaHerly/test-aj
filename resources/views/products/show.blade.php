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

            {{-- ── Kartu Sertifikat Kualitas ── --}}
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-header bg-white border-bottom px-4 pt-4 pb-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-award-fill me-2 text-success"></i>Sertifikasi & Kualitas Barang
                    </h5>
                </div>
                <div class="card-body px-4 py-3">
                    @if($product->certifications && $product->certifications->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jenis Sertifikat</th>
                                        <th>Nomor Lot</th>
                                        <th>Region Standar</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->certifications as $cert)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $cert->certification_type }}</div>
                                                <div class="text-muted small" style="font-size: 0.75rem;">Oleh: {{ $cert->certifier->name ?? '—' }}</div>
                                            </td>
                                            <td><code class="text-dark small">{{ $cert->lot_number }}</code></td>
                                            <td>{{ $cert->standard_region }}</td>
                                            <td class="small">{{ $cert->certification_date->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                @if($cert->status === 'valid')
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Valid</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">{{ ucfirst($cert->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ $cert->document_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>Unduh
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-award fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0 small">Belum ada dokumen sertifikat kualitas yang diunggah untuk barang ini.</p>
                            <p class="text-muted small" style="font-size:0.75rem;">Sertifikat dapat diunggah melalui menu Operasional Harian > Sertifikasi.</p>
                        </div>
                    @endif
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

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small text-uppercase fw-semibold">Stok Reject</div>
                                <div class="fs-4 fw-bold text-danger">
                                    {{ number_format($product->rejectItems()->sum('qty_rejected')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="text-muted small text-uppercase fw-semibold">Stok Layak</div>
                                <div class="fs-4 fw-bold {{ $product->isLowStock() ? 'text-danger' : 'text-success' }}">
                                    {{ $product->stock_qty }}
                                </div>
                            </div>
                        </div>
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
