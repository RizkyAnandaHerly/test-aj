@extends('layouts.sidebar')

@section('title', 'Edit Sales Order')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0 text-dark">Edit Sales Order</h4>
    <p class="text-muted mb-0 small">Perbarui informasi pesanan #{{ $salesOrder->order_number }}</p>
</div>

<div class="card border-0 shadow-sm rounded-3" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ route('sales-orders.update', $salesOrder->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="order_number" class="form-label fw-semibold">Nomor Pesanan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('order_number') is-invalid @enderror" id="order_number" name="order_number" value="{{ old('order_number', $salesOrder->order_number) }}" required>
                @error('order_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="customer_name" class="form-label fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', $salesOrder->customer_name) }}" required>
                @error('customer_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="total_amount" class="form-label fw-semibold">Total Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount', floatval($salesOrder->total_amount)) }}" required min="0" step="1">
                @error('total_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="pending" {{ old('status', $salesOrder->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ old('status', $salesOrder->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ old('status', $salesOrder->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $salesOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('sales-orders.index') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Pesanan</button>
            </div>
        </form>
    </div>
</div>
@endsection