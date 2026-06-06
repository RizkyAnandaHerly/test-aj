@extends('layouts.sidebar')
@section('title', 'Form Packing & Pelabelan')
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
                    <div class="bg-primary-subtle text-primary rounded p-2 me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Form Packing & Pelabelan</h5>
                        <p class="text-muted small mb-0">Catat detail fisik packing dan buat label barang.</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('packing-details.store') }}" method="POST">
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
                                                data-product-id="{{ $inbound->product_id }}"
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
                                        class="form-select form-select-lg border-0 @error('product_id') is-invalid @enderror"
                                        style="pointer-events: none; background-color: #e9ecef;"
                                        required>
                                    <option value="" selected disabled>-- Produk Akan Terisi Otomatis --</option>
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
                                <label for="quantity" class="form-label fw-semibold small">
                                    Qty Packing <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="quantity"
                                       id="quantity"
                                       min="1"
                                       class="form-control form-control-lg bg-light border-0 @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity') }}"
                                       required>
                                @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="packaging_type" class="form-label fw-semibold small">
                                    Tipe Packaging <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="packaging_type"
                                       id="packaging_type"
                                       class="form-control form-control-lg bg-light border-0 @error('packaging_type') is-invalid @enderror"
                                       value="{{ old('packaging_type') }}"
                                       placeholder="Cth: Box Kardus, Palet, Bubble Wrap"
                                       required>
                                @error('packaging_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="package_weight" class="form-label fw-semibold small">
                                    Berat Paket
                                </label>
                                <input type="text"
                                       name="package_weight"
                                       id="package_weight"
                                       class="form-control form-control-lg bg-light border-0 @error('package_weight') is-invalid @enderror"
                                       value="{{ old('package_weight') }}"
                                       placeholder="Cth: 12 kg">
                                @error('package_weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="package_dimensions" class="form-label fw-semibold small">
                                Dimensi Paket
                            </label>
                            <input type="text"
                                   name="package_dimensions"
                                   id="package_dimensions"
                                   class="form-control form-control-lg bg-light border-0 @error('package_dimensions') is-invalid @enderror"
                                   value="{{ old('package_dimensions') }}"
                                   placeholder="Cth: 60x40x30 cm">
                            @error('package_dimensions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold small">
                                Catatan Fisik / Label
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="4"
                                      class="form-control bg-light border-0 @error('notes') is-invalid @enderror"
                                      placeholder="Isi kondisi fisik, instruksi label atau keterangan packing...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('packing-details.index') }}"
                               class="btn btn-light border px-4 me-2 fw-semibold">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Simpan Packing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inboundSelect = document.getElementById('inbound_id');
            const productSelect = document.getElementById('product_id');

            // Jalankan fungsi update saat inbound dipilih
            if (inboundSelect && productSelect) {
                inboundSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const productId = selectedOption.getAttribute('data-product-id');

                    if (productId) {
                        productSelect.value = productId;
                    } else {
                        productSelect.value = '';
                    }
                });

                // Cek jika halaman direfresh dan inbound sudah terpilih (misal karena error form)
                if(inboundSelect.value !== "") {
                    // Trigger event change secara manual
                    inboundSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
@endsection