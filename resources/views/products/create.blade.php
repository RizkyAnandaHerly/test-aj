@extends('layouts.sidebar')
@section('title', 'Tambah Produk Master')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan!</h6>
                    <ul class="mb-0 small ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Tambah Produk Baru</h5>
                        <p class="text-muted small mb-0">Daftarkan item barang baru ke dalam katalog master sistem</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Informasi Barang</h6>

                        <div class="row mb-3">
                            {{-- SKU --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="sku" class="form-label fw-semibold small">Stock Keeping Unit (SKU) <span class="text-danger">*</span></label>
                                <input type="text" name="sku" id="sku"
                                       class="form-control form-control-lg bg-light border-0 @error('sku') is-invalid @enderror"
                                       placeholder="Contoh: BRG-001"
                                       value="{{ old('sku') }}" required>
                                @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Nama Barang --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold small">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control form-control-lg bg-light border-0 @error('name') is-invalid @enderror"
                                       placeholder="Contoh: Lampu LED Philip 10W"
                                       value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            {{-- Kategori --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="category" class="form-label fw-semibold small">Kategori</label>
                                <input type="text" name="category" id="category"
                                       class="form-control form-control-lg bg-light border-0 @error('category') is-invalid @enderror"
                                       placeholder="Contoh: Elektronik"
                                       value="{{ old('category') }}">
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Satuan Unit --}}
                            <div class="col-md-6">
                                <label for="unit" class="form-label fw-semibold small">Satuan Unit <span class="text-danger">*</span></label>
                                <input type="text" name="unit" id="unit"
                                       class="form-control form-control-lg bg-light border-0 @error('unit') is-invalid @enderror"
                                       placeholder="Contoh: pcs, kg, box, pack"
                                       value="{{ old('unit', 'pcs') }}" required>
                                @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold small">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control bg-light border-0 @error('description') is-invalid @enderror"
                                      placeholder="Keterangan spesifikasi barang secara detail...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2 mt-4">Aturan Persediaan</h6>

                        <div class="row mb-4">
                            {{-- Batas Minimum Stok --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="min_stock" class="form-label fw-semibold small">Batas Minimum Stok <span class="text-danger">*</span></label>
                                <input type="number" name="min_stock" id="min_stock" min="0"
                                       class="form-control form-control-lg bg-light border-0 @error('min_stock') is-invalid @enderror"
                                       placeholder="Contoh: 10"
                                       value="{{ old('min_stock', 0) }}" required>
                                <div class="form-text small text-muted">Sistem akan memberi peringatan jika stok berada di bawah batas ini.</div>
                                @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold small">Status Produk <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select form-select-lg bg-light border-0 @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Upload Gambar (Opsional) --}}
                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold small">Foto Produk (Opsional)</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="form-control bg-light border-0 @error('image') is-invalid @enderror">
                            <div class="form-text small text-muted">Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal 2MB.</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Aksi --}}
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-light border px-4 me-2 fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Produk
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
