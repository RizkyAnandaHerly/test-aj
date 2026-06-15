@extends('layouts.sidebar')
@section('title', 'Edit Stock Opname')
@section('content')

    <div class="row">
        <div class="col-lg-12">

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

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
                    <span class="fw-semibold">{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger flex-shrink-0"></i>
                    <span class="fw-semibold">{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                            <i class="bi bi-calculator fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ $stockOpname->opname_number }}</h5>
                            <p class="text-muted small mb-0">{{ $stockOpname->warehouse->name }} — {{ $stockOpname->opname_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div>
                        @switch($stockOpname->status)
                            @case('in_progress')
                                <span class="badge bg-info fs-6">Sedang Berjalan</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success fs-6">Selesai</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger fs-6">Dibatalkan</span>
                                @break
                        @endswitch
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Gudang</p>
                            <p class="fw-semibold text-dark">{{ $stockOpname->warehouse->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Dibuat Oleh</p>
                            <p class="fw-semibold text-dark">{{ $stockOpname->creator->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Total Detail</p>
                            <p class="fw-semibold text-dark">{{ $stockOpname->details->count() }} item</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input Perhitungan Fisik --}}
            @if($stockOpname->status !== 'completed')
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                        <div class="bg-success-subtle text-success rounded p-2 me-3">
                            <i class="bi bi-plus-circle fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Input Perhitungan Fisik</h5>
                            <p class="text-muted small mb-0">Masukkan jumlah stok hasil perhitungan fisik</p>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('stock-opnames.update-detail', $stockOpname->id) }}" method="POST" class="row g-3 align-items-end">
                            @csrf

                            <div class="col-md-6">
                                <label for="product_location_id" class="form-label fw-semibold small">
                                    Produk & Lokasi <span class="text-danger">*</span>
                                </label>
                                <select name="product_location_id" id="product_location_id"
                                        class="form-select form-select-lg bg-light border-0"
                                        required>
                                    <option value="" selected disabled>-- Pilih Produk & Lokasi --</option>
                                    @foreach($productLocations as $pl)
                                        <option value="{{ $pl->id }}">
                                            {{ $pl->product->sku }} — {{ $pl->product->name }} @ 
                                            {{ $pl->location->zone }}/{{ $pl->location->rack_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="physical_qty" class="form-label fw-semibold small">
                                    Jumlah Fisik <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="physical_qty" id="physical_qty" min="0"
                                       class="form-control form-control-lg bg-light border-0"
                                       placeholder="0"
                                       required>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-lg btn-success w-100">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Detail Perhitungan --}}
            @if($stockOpname->details->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                        <div class="bg-info-subtle text-info rounded p-2 me-3">
                            <i class="bi bi-list-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Detail Perhitungan Fisik</h5>
                            <p class="text-muted small mb-0">Hasil perbandingan antara stok sistem vs stok fisik</p>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Lokasi</th>
                                        <th class="text-center">Stok Sistem</th>
                                        <th class="text-center">Stok Fisik</th>
                                        <th class="text-center">Selisih</th>
                                        @if($stockOpname->status === 'in_progress')
                                            <th class="text-center" style="width:80px;">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockOpname->details as $detail)
                                        <tr>
                                            <td>
                                                <p class="fw-semibold text-dark mb-0">{{ $detail->product->sku }}</p>
                                                <p class="text-muted small mb-0">{{ $detail->product->name }}</p>
                                            </td>
                                            <td>
                                                {{ $detail->productLocation->location->zone }}/{{ $detail->productLocation->location->rack_code }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $detail->system_qty }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $detail->physical_qty }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($detail->difference > 0)
                                                    <span class="badge bg-success">+{{ $detail->difference }}</span>
                                                @elseif($detail->difference < 0)
                                                    <span class="badge bg-danger">{{ $detail->difference }}</span>
                                                @else
                                                    <span class="badge bg-secondary">0</span>
                                                @endif
                                            </td>
                                            @if($stockOpname->status === 'in_progress')
                                                <td class="text-center">
                                                    <form action="{{ route('stock-opnames.destroy-detail', [$stockOpname->id, $detail->id]) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Yakin ingin menghapus detail perhitungan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus detail">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if($stockOpname->status === 'in_progress')
                    <div class="mt-4 d-grid gap-2 d-sm-flex gap-2 justify-content-sm-end">
                        <form action="{{ route('stock-opnames.cancel', $stockOpname->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan opname ini?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle me-2"></i>Batalkan
                            </button>
                        </form>
                        <form action="{{ route('stock-opnames.apply', $stockOpname->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menerapkan penyesuaian stok ini?')">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Terapkan Penyesuaian
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center" @if($stockOpname->status === 'completed') style="background: #f0fdf4; border: 1px solid #bbf7d0;" @endif>
                    <i class="bi bi-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">Belum ada data perhitungan fisik</p>
                </div>
            @endif

            {{-- Adjustment Logs (jika sudah completed) --}}
            @if($stockOpname->status === 'completed' && $stockOpname->adjustmentLogs->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                        <div class="bg-warning-subtle text-warning rounded p-2 me-3">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Log Penyesuaian Stok</h5>
                            <p class="text-muted small mb-0">Riwayat penyesuaian yang telah diterapkan</p>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Lokasi</th>
                                        <th>Jenis Penyesuaian</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Alasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockOpname->adjustmentLogs as $log)
                                        <tr>
                                            <td>{{ $log->product->sku }} — {{ $log->product->name }}</td>
                                            <td>{{ $log->location->zone }}/{{ $log->location->rack_code }}</td>
                                            <td>
                                                @if($log->adjustment_type === 'increase')
                                                    <span class="badge bg-success">Penambahan</span>
                                                @else
                                                    <span class="badge bg-danger">Pengurangan</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $log->adjustment_qty }}</td>
                                            <td>{{ $log->reason }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('stock-opnames.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

        </div>
    </div>

@endsection
