@extends('layouts.sidebar')

@section('title', 'Detail Sales Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Detail Sales Order</h4>
        <p class="text-muted mb-0 small">Informasi lengkap untuk pesanan #{{ $salesOrder->order_number }}</p>
    </div>
    <a href="{{ route('sales-orders.index') }}" class="btn btn-light border shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3" style="max-width: 800px;">
    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
        <h6 class="fw-bold mb-0 text-primary">
            <i class="bi bi-receipt me-2"></i>Informasi Pesanan
        </h6>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-sm-4 text-muted fw-semibold">Nomor Pesanan</div>
            <div class="col-sm-8 text-dark">{{ $salesOrder->order_number }}</div>
        </div>
        <hr class="text-muted opacity-25">
        
        <div class="row mb-3">
            <div class="col-sm-4 text-muted fw-semibold">Nama Pelanggan</div>
            <div class="col-sm-8 text-dark">{{ $salesOrder->customer_name }}</div>
        </div>
        <hr class="text-muted opacity-25">

        <div class="row mb-3">
            <div class="col-sm-4 text-muted fw-semibold">Total Harga</div>
            <div class="col-sm-8 text-dark fw-bold text-primary">
                Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}
            </div>
        </div>
        <hr class="text-muted opacity-25">

        <div class="row mb-3">
            <div class="col-sm-4 text-muted fw-semibold">Status</div>
            <div class="col-sm-8">
                @if($salesOrder->status === 'completed')
                    <span class="badge rounded-pill bg-success text-white px-3">Completed</span>
                @elseif($salesOrder->status === 'cancelled')
                    <span class="badge rounded-pill bg-danger text-white px-3">Cancelled</span>
                @elseif($salesOrder->status === 'processing')
                    <span class="badge rounded-pill bg-primary text-white px-3">Processing</span>
                @else
                    <span class="badge rounded-pill bg-warning text-dark px-3">Pending</span>
                @endif
            </div>
        </div>
        <hr class="text-muted opacity-25">

        <div class="row mb-3">
            <div class="col-sm-4 text-muted fw-semibold">Tanggal Dibuat</div>
            <div class="col-sm-8 text-dark">{{ $salesOrder->created_at->translatedFormat('l, d F Y H:i') }}</div>
        </div>

    </div>
    <div class="card-footer bg-light border-top py-3 px-4 d-flex justify-content-end gap-2">
        <a href="{{ route('sales-orders.edit', $salesOrder->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit Pesanan
        </a>
        <form action="{{ route('sales-orders.destroy', $salesOrder->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection