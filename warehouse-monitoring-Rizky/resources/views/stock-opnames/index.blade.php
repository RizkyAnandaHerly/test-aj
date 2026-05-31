@extends('layouts.sidebar')
@section('title', 'Stock Opname')
@section('content')

    <div class="row align-items-center mb-4">
        <div class="col">
            <h4 class="fw-bold text-dark mb-0">Daftar Stock Opname</h4>
            <p class="text-muted small mb-0">Kelola perhitungan fisik stok barang di gudang</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('stock-opnames.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-2"></i>Stock Opname Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-circle-fill text-danger flex-shrink-0"></i>
            <span class="fw-semibold">{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($opnames->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="bi bi-box-seam text-muted mb-3" style="font-size: 3rem;"></i>
            <p class="text-muted">Belum ada stock opname yang dibuat</p>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor Opname</th>
                            <th>Gudang</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Detail</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($opnames as $opname)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $opname->opname_number }}</td>
                                <td>{{ $opname->warehouse->name ?? '-' }}</td>
                                <td>{{ $opname->opname_date->format('d M Y') }}</td>
                                <td>
                                    @switch($opname->status)
                                        @case('draft')
                                            <span class="badge bg-secondary">Draft</span>
                                            @break
                                        @case('in_progress')
                                            <span class="badge bg-info">Sedang Berjalan</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Selesai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $opname->creator->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $opname->details->count() }} item</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('stock-opnames.show', $opname->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('stock-opnames.edit', $opname->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
