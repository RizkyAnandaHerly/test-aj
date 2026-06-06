@extends('layouts.sidebar')
@section('title', 'Detail Dokumen Pengiriman')
@section('content')

    <div class="row">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ $shippingDocument->document_number }}</h5>
                            <p class="text-muted small mb-0">
                                @switch($shippingDocument->document_type)
                                    @case('suratjalan') Surat Jalan @break
                                    @case('coo') Certificate of Origin @break
                                    @case('pob') Proof of Business @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                    <div>
                        @switch($shippingDocument->status)
                            @case('draft')
                                <span class="badge bg-secondary fs-6">Draft</span>
                                @break
                            @case('issued')
                                <span class="badge bg-primary fs-6">Diterbitkan</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success fs-6">Selesai</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger fs-6">Dibatalkan</span>
                                @break
                        @endswitch
                    </div>
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Informasi Dokumen</h6>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Nomor Dokumen</p>
                            <p class="fw-semibold text-dark">{{ $shippingDocument->document_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tanggal Diterbitkan</p>
                            <p class="fw-semibold text-dark">{{ $shippingDocument->issued_date->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Sales Order</p>
                            <p class="fw-semibold text-dark">{{ $shippingDocument->salesOrder->order_number ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Dibuat Oleh</p>
                            <p class="fw-semibold text-dark">{{ $shippingDocument->creator->name ?? '-' }}</p>
                        </div>
                    </div>

                    @if($shippingDocument->notes)
                        <div class="mb-4">
                            <p class="text-muted small mb-1">Catatan</p>
                            <p class="text-dark">{{ $shippingDocument->notes }}</p>
                        </div>
                    @endif

                    <div class="d-grid gap-2 d-sm-flex gap-2 justify-content-sm-end mt-4">
                        <a href="{{ route('shipping-documents.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        <a href="{{ route('shipping-documents.edit', $shippingDocument->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
