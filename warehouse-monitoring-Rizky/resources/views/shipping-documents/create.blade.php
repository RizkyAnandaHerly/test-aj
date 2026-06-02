@extends('layouts.sidebar')
@section('title', 'Buat Dokumen Pengiriman')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <strong>Perbaiki kesalahan berikut:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Buat Dokumen Pengiriman</h5>
                        <p class="text-muted small mb-0">Tambahkan dokumen pengiriman baru (Surat Jalan, COO, atau POB)</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('shipping-documents.store') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Informasi Dokumen</h6>

                        {{-- Sales Order --}}
                        <div class="mb-4">
                            <label for="sales_order_id" class="form-label fw-semibold small">
                                Sales Order <span class="text-danger">*</span>
                            </label>
                            <select name="sales_order_id" id="sales_order_id"
                                    class="form-select form-select-lg bg-light border-0 @error('sales_order_id') is-invalid @enderror"
                                    required>
                                <option value="" selected disabled>-- Pilih Sales Order --</option>
                                @foreach($salesOrders as $so)
                                    <option value="{{ $so->id }}" {{ old('sales_order_id') == $so->id ? 'selected' : '' }}>
                                        {{ $so->order_number }} — {{ $so->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_order_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        {{-- Tipe Dokumen --}}
                        <div class="mb-4">
                            <label for="document_type" class="form-label fw-semibold small">
                                Tipe Dokumen <span class="text-danger">*</span>
                            </label>
                            <select name="document_type" id="document_type"
                                    class="form-select form-select-lg bg-light border-0 @error('document_type') is-invalid @enderror"
                                    required>
                                <option value="" selected disabled>-- Pilih Tipe --</option>
                                <option value="suratjalan" {{ old('document_type') == 'suratjalan' ? 'selected' : '' }}>Surat Jalan</option>
                                <option value="coo" {{ old('document_type') == 'coo' ? 'selected' : '' }}>COO (Certificate of Origin)</option>
                                <option value="pob" {{ old('document_type') == 'pob' ? 'selected' : '' }}>POB (Proof of Business)</option>
                            </select>
                            @error('document_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        {{-- Tanggal Diterbitkan --}}
                        <div class="mb-4">
                            <label for="issued_date" class="form-label fw-semibold small">
                                Tanggal Diterbitkan <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="issued_date" id="issued_date"
                                   class="form-control form-control-lg bg-light border-0 @error('issued_date') is-invalid @enderror"
                                   value="{{ old('issued_date', date('Y-m-d')) }}" required>
                            @error('issued_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold small">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="form-control form-control-lg bg-light border-0 @error('notes') is-invalid @enderror"
                                      placeholder="Tambahkan catatan atau informasi tambahan...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid gap-2 d-sm-flex gap-2 justify-content-sm-end">
                            <a href="{{ route('shipping-documents.index') }}" class="btn btn-lg btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Simpan Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
