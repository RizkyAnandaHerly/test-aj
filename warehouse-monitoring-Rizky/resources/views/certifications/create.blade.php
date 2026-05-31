@extends('layouts.sidebar')
@section('title', 'Form Sertifikasi')
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
                    <div class="bg-warning-subtle text-warning rounded p-2 me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-award fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Form Sertifikasi</h5>
                        <p class="text-muted small mb-0">Unggah dokumen sertifikasi dan data ketertelusuran lot kopi.</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('certifications.store') }}" method="POST" enctype="multipart/form-data">
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
                                            | Batch: {{ $inbound->batch_no ?? '—' }}
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
                                <label for="certification_date" class="form-label fw-semibold small">
                                    Tanggal Sertifikasi <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="certification_date"
                                       id="certification_date"
                                       class="form-control form-control-lg bg-light border-0 @error('certification_date') is-invalid @enderror"
                                       value="{{ old('certification_date', date('Y-m-d')) }}"
                                       required>
                                @error('certification_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="certification_type" class="form-label fw-semibold small">
                                    Tipe Dokumen Sertifikasi <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="certification_type"
                                       id="certification_type"
                                       class="form-control form-control-lg bg-light border-0 @error('certification_type') is-invalid @enderror"
                                       value="{{ old('certification_type') }}"
                                       placeholder="Cth: Certificate of Origin / Phytosanitary"
                                       required>
                                @error('certification_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="lot_number" class="form-label fw-semibold small">
                                    Nomor Lot / Traceability <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="lot_number"
                                       id="lot_number"
                                       class="form-control form-control-lg bg-light border-0 @error('lot_number') is-invalid @enderror"
                                       value="{{ old('lot_number') }}"
                                       placeholder="Cth: LOT-20260519-01"
                                       required>
                                @error('lot_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="standard_region" class="form-label fw-semibold small">
                                    Standar Dokumen <span class="text-danger">*</span>
                                </label>
                                <select name="standard_region"
                                        id="standard_region"
                                        class="form-select form-select-lg bg-light border-0 @error('standard_region') is-invalid @enderror"
                                        required>
                                    <option value="Eropa" {{ old('standard_region', 'Eropa') === 'Eropa' ? 'selected' : '' }}>Eropa</option>
                                    <option value="Global" {{ old('standard_region') === 'Global' ? 'selected' : '' }}>Global</option>
                                    <option value="Lokal" {{ old('standard_region') === 'Lokal' ? 'selected' : '' }}>Lokal</option>
                                </select>
                                @error('standard_region')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="document" class="form-label fw-semibold small">
                                    Unggah Dokumen Sertifikasi <span class="text-danger">*</span>
                                </label>
                                <input type="file"
                                       name="document"
                                       id="document"
                                       class="form-control form-control-lg bg-light border-0 @error('document') is-invalid @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       required>
                                @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold small">
                                Catatan Ekspor / Validasi
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="4"
                                      class="form-control bg-light border-0 @error('notes') is-invalid @enderror"
                                      placeholder="Tuliskan validasi khusus, persyaratan ekspor, atau catatan traceability...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('certifications.index') }}"
                               class="btn btn-light border px-4 me-2 fw-semibold">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Simpan Sertifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
