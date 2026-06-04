@extends('layouts.sidebar')
@section('title', 'Form Reject & Karantina')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pada form:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-danger-subtle text-danger rounded p-2 me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-slash-circle fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Form Reject & Karantina</h5>
                        <p class="text-muted small mb-0">Catat barang tidak layak jual dan lokasi karantina jika diperlukan.</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('reject-items.store') }}" method="POST">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="inbound_id" class="form-label fw-semibold small">
                                    Pilih Inbound Sumber Barang <span class="text-danger">*</span>
                                </label>
                                <select name="inbound_id"
                                        id="inbound_id"
                                        class="form-select form-select-lg bg-light border-0 @error('inbound_id') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>-- Pilih Inbound --</option>
                                    @foreach($inbounds as $inbound)
                                        <option value="{{ $inbound->id }}"
                                            {{ old('inbound_id') == $inbound->id ? 'selected' : '' }}>
                                            #{{ $inbound->id }} – {{ $inbound->product->sku ?? '—' }}
                                            | {{ $inbound->product->name ?? '—' }}
                                            | {{ $inbound->received_date?->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('inbound_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="product_id" class="form-label fw-semibold small">
                                    Produk <span class="text-danger">*</span>
                                </label>
                                <select name="product_id"
                                        id="product_id"
                                        class="form-select form-select-lg bg-light border-0 @error('product_id') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->sku }} — {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="qty_rejected" class="form-label fw-semibold small">
                                    Jumlah Reject <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="qty_rejected"
                                       id="qty_rejected"
                                       min="1"
                                       class="form-control form-control-lg bg-light border-0 @error('qty_rejected') is-invalid @enderror"
                                       value="{{ old('qty_rejected') }}"
                                       required>
                                @error('qty_rejected')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label fw-semibold small">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select name="category"
                                        id="category"
                                        class="form-select form-select-lg bg-light border-0 @error('category') is-invalid @enderror"
                                        required>
                                    <option value="reject" {{ old('category') === 'reject' ? 'selected' : '' }}>Reject</option>
                                    <option value="quarantine" {{ old('category') === 'quarantine' ? 'selected' : '' }}>Karantina</option>
                                </select>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="quarantine_location" class="form-label fw-semibold small">
                                    Lokasi Karantina
                                </label>
                                <input type="text"
                                       name="quarantine_location"
                                       id="quarantine_location"
                                       class="form-control form-control-lg bg-light border-0 @error('quarantine_location') is-invalid @enderror"
                                       value="{{ old('quarantine_location') }}"
                                       placeholder="Rak karantina / gudang khusus">
                                @error('quarantine_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="form-label fw-semibold small">
                                Alasan Reject / Catatan Kerusakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="reason"
                                      id="reason"
                                      rows="4"
                                      class="form-control bg-light border-0 @error('reason') is-invalid @enderror"
                                      placeholder="Tuliskan kondisi barang, alasan reject, atau catatan karantina..." required>{{ old('reason') }}</textarea>
                            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('reject-items.index') }}"
                               class="btn btn-light border px-4 me-2 fw-semibold">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-danger px-5 fw-bold shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Simpan Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
