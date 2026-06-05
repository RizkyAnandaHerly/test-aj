@extends('layouts.sidebar')
@section('title', 'Daftar Reject & Karantina')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-11">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Daftar Barang Reject & Karantina</h4>
                    <p class="text-muted small mb-0">Semua catatan barang cacat, reject, atau dikarantina.</p>
                </div>
                <a href="{{ route('reject-items.create') }}" class="btn btn-success fw-semibold px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Reject
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Inbound</th>
                                    <th>Produk</th>
                                    <th>Qty Reject</th>
                                    <th>Kategori</th>
                                    <th>Catatan</th>
                                    <th>Lokasi Karantina</th>
                                    <th>Ditandai Oleh</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rejects as $reject)
                                    <tr>
                                        <td>{{ $reject->id }}</td>
                                        <td>#{{ $reject->inbound->id }}
                                            <div class="text-muted small">{{ $reject->inbound->product->sku ?? '—' }}</div>
                                        </td>
                                        <td>
                                            {{ $reject->product->name ?? '—' }}<br>
                                            <small class="text-muted">{{ $reject->product->sku ?? '—' }}</small>
                                        </td>
                                        <td>{{ number_format($reject->qty_rejected) }}</td>
                                        <td class="text-capitalize">
                                            {{ $reject->category === 'quarantine' ? 'Karantina' : 'Reject' }}
                                        </td>
                                        <td>{{ Str::limit($reject->reason, 50, '...') }}</td>
                                        <td>{{ $reject->quarantine_location ?? '—' }}</td>
                                        <td>{{ $reject->inspector->name ?? '—' }}</td>
                                        <td>{{ $reject->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            Belum ada barang reject atau karantina.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
