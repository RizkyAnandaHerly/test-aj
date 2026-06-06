@extends('layouts.sidebar')
@section('title', 'Tambah Gudang')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                        <i class="bi bi-building-add fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Tambah Gudang Baru</h5>
                        <p class="text-muted small mb-0">Daftarkan gedung gudang baru ke dalam sistem</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('warehouses.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="name" class="form-label fw-semibold small">Nama Gudang <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control bg-light border-0 @error('name') is-invalid @enderror"
                                       placeholder="Contoh: Gudang Utama Bogor"
                                       value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="code" class="form-label fw-semibold small">Kode Unik <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code"
                                       class="form-control bg-light border-0 @error('code') is-invalid @enderror"
                                       placeholder="Contoh: WH-BG-01"
                                       value="{{ old('code') }}" required>
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold small">Alamat</label>
                            <textarea name="address" id="address" rows="2"
                                      class="form-control bg-light border-0 @error('address') is-invalid @enderror"
                                      placeholder="Jl. Raya Bogor No. 10...">{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="pic_name" class="form-label fw-semibold small">PIC (Penanggung Jawab)</label>
                                <input type="text" name="pic_name" id="pic_name"
                                       class="form-control bg-light border-0 @error('pic_name') is-invalid @enderror"
                                       placeholder="Nama penanggung jawab"
                                       value="{{ old('pic_name') }}">
                                @error('pic_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold small">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone"
                                       class="form-control bg-light border-0 @error('phone') is-invalid @enderror"
                                       placeholder="Contoh: 0812-3456-7890"
                                       value="{{ old('phone') }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                    class="form-select bg-light border-0 @error('status') is-invalid @enderror" required>
                                <option value="active"   {{ old('status', 'active') === 'active'   ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <a href="{{ route('warehouses.index') }}" class="btn btn-light border px-4 me-2 fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
