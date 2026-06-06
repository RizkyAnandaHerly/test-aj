@extends('layouts.sidebar')
@section('title', 'Edit Vendor')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning rounded p-2 me-3">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Edit Vendor — {{ $vendor->name }}</h5>
                        <p class="text-muted small mb-0">Perbarui data vendor</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('vendors.update', $vendor) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="name" class="form-label fw-semibold small">Nama Vendor <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control bg-light border-0 @error('name') is-invalid @enderror"
                                       value="{{ old('name', $vendor->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="code" class="form-label fw-semibold small">Kode Unik <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code"
                                       class="form-control bg-light border-0 @error('code') is-invalid @enderror"
                                       value="{{ old('code', $vendor->code) }}" required>
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="contact_person" class="form-label fw-semibold small">Nama Kontak</label>
                                <input type="text" name="contact_person" id="contact_person"
                                       class="form-control bg-light border-0 @error('contact_person') is-invalid @enderror"
                                       value="{{ old('contact_person', $vendor->contact_person) }}">
                                @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold small">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone"
                                       class="form-control bg-light border-0 @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $vendor->phone) }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="email" class="form-label fw-semibold small">Email</label>
                                <input type="email" name="email" id="email"
                                       class="form-control bg-light border-0 @error('email') is-invalid @enderror"
                                       value="{{ old('email', $vendor->email) }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="form-label fw-semibold small">Kota</label>
                                <input type="text" name="city" id="city"
                                       class="form-control bg-light border-0 @error('city') is-invalid @enderror"
                                       value="{{ old('city', $vendor->city) }}">
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold small">Alamat</label>
                            <textarea name="address" id="address" rows="2"
                                      class="form-control bg-light border-0 @error('address') is-invalid @enderror">{{ old('address', $vendor->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                    class="form-select bg-light border-0 @error('status') is-invalid @enderror" required>
                                <option value="active"   {{ old('status', $vendor->status) === 'active'   ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $vendor->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <a href="{{ route('vendors.index') }}" class="btn btn-light border px-4 me-2 fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
