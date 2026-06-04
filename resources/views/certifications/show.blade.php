@extends('layouts.sidebar')
@section('title', 'Detail Sertifikasi')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Detail Sertifikasi</h5>
                        <p class="text-muted small mb-0">Dokumen sertifikasi & data traceability lot kopi.</p>
                    </div>
                    <span class="badge bg-success text-uppercase">{{ $certification->status }}</span>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small mb-1">Produk</div>
                            <div class="fw-semibold text-dark">{{ $certification->product->name ?? '—' }}</div>
                            <div class="text-muted small">{{ $certification->product->sku ?? '—' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small mb-1">Batch / Lot</div>
                            <div class="fw-semibold text-dark">{{ $certification->lot_number }}</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="text-muted small mb-1">Tanggal Sertifikasi</div>
                            <div class="fw-semibold text-dark">{{ $certification->certification_date->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-muted small mb-1">Standar Dokumen</div>
                            <div class="fw-semibold text-dark">{{ $certification->standard_region }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-muted small mb-1">Certifier</div>
                            <div class="fw-semibold text-dark">{{ $certification->certifier->name ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="text-muted small mb-1">Tipe Dokumen</div>
                        <div class="fw-semibold text-dark">{{ $certification->certification_type }}</div>
                    </div>

                    <div class="mb-4">
                        <div class="text-muted small mb-1">Dokumen Sertifikasi</div>
                        <a href="{{ $certification->document_url }}" class="btn btn-sm btn-outline-warning" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i> {{ $certification->document_name }}
                        </a>
                    </div>

                    <div class="mb-4">
                        <div class="text-muted small mb-1">Catatan Validasi</div>
                        <div class="fw-semibold text-dark">{{ $certification->notes ?? '—' }}</div>
                    </div>

                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <a href="{{ route('certifications.index') }}" class="btn btn-light border px-4 fw-semibold">
                            Kembali ke Daftar
                        </a>
                        <a href="{{ route('certifications.create') }}" class="btn btn-warning px-4 fw-bold">
                            Tambah Sertifikasi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
