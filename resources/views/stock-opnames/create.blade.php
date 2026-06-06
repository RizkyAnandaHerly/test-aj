@extends('layouts.sidebar')
@section('title', 'Buat Stock Opname Baru')
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
                        <i class="bi bi-calculator fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Buat Stock Opname Baru</h5>
                        <p class="text-muted small mb-0">Mulai perhitungan fisik stok barang di gudang</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('stock-opnames.store') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Informasi Opname</h6>

                        {{-- Pilih Gudang --}}
                        <div class="mb-4">
                            <label for="warehouse_id" class="form-label fw-semibold small">
                                Pilih Gudang <span class="text-danger">*</span>
                            </label>
                            <select name="warehouse_id" id="warehouse_id"
                                    class="form-select form-select-lg bg-light border-0 @error('warehouse_id') is-invalid @enderror"
                                    required>
                                <option value="" selected disabled>-- Pilih Gudang --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                        {{ $wh->code }} — {{ $wh->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        {{-- Tanggal Opname --}}
                        <div class="mb-4">
                            <label for="opname_date" class="form-label fw-semibold small">
                                Tanggal Opname <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="opname_date" id="opname_date"
                                   class="form-control form-control-lg bg-light border-0 @error('opname_date') is-invalid @enderror"
                                   value="{{ old('opname_date', date('Y-m-d')) }}" required>
                            @error('opname_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                            <a href="{{ route('stock-opnames.index') }}" class="btn btn-lg btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Buat Opname
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
