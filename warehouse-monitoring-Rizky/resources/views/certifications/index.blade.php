@extends('layouts.sidebar')
@section('title', 'Daftar Sertifikasi')
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
                    <h4 class="fw-bold mb-1">Daftar Sertifikasi</h4>
                    <p class="text-muted small mb-0">Riwayat dokumen sertifikasi dan data traceability lot kopi.</p>
                </div>
                <a href="{{ route('certifications.create') }}" class="btn btn-warning fw-semibold px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Sertifikasi
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Lot</th>
                                    <th>Standar</th>
                                    <th>Status</th>
                                    <th>Certifier</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($certifications as $certification)
                                    <tr>
                                        <td>{{ $certification->id }}</td>
                                        <td>
                                            {{ $certification->product->name ?? '—' }}<br>
                                            <small class="text-muted">{{ $certification->product->sku ?? '—' }}</small>
                                        </td>
                                        <td>{{ $certification->lot_number }}</td>
                                        <td>{{ $certification->standard_region }}</td>
                                        <td class="text-capitalize">
                                            @if($certification->status === 'valid')
                                                <span class="badge bg-success">Valid</span>
                                            @elseif($certification->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $certification->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $certification->certifier->name ?? '—' }}</td>
                                        <td>{{ $certification->certification_date->format('d/m/Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('certifications.show', $certification) }}" class="btn btn-sm btn-outline-primary">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Belum ada data sertifikasi.
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
