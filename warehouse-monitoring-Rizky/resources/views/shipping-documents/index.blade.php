@extends('layouts.sidebar')
@section('title', 'Dokumen Pengiriman')
@section('content')

    <div class="row align-items-center mb-4">
        <div class="col">
            <h4 class="fw-bold text-dark mb-0">Daftar Dokumen Pengiriman</h4>
            <p class="text-muted small mb-0">Kelola dokumen pengiriman (Surat Jalan, COO, POB)</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('shipping-documents.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-2"></i>Dokumen Baru
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

    @if($documents->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="bi bi-file-text text-muted mb-3" style="font-size: 3rem;"></i>
            <p class="text-muted">Belum ada dokumen pengiriman yang dibuat</p>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor Dokumen</th>
                            <th>Tipe</th>
                            <th>Sales Order</th>
                            <th>Tanggal Diterbitkan</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $doc->document_number }}</td>
                                <td>
                                    @switch($doc->document_type)
                                        @case('suratjalan')
                                            <span class="badge bg-info">Surat Jalan</span>
                                            @break
                                        @case('coo')
                                            <span class="badge bg-warning">COO</span>
                                            @break
                                        @case('pob')
                                            <span class="badge bg-secondary">POB</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $doc->salesOrder->order_number ?? '-' }}</td>
                                <td>{{ $doc->issued_date->format('d M Y') }}</td>
                                <td>
                                    @switch($doc->status)
                                        @case('draft')
                                            <span class="badge bg-secondary">Draft</span>
                                            @break
                                        @case('issued')
                                            <span class="badge bg-primary">Diterbitkan</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Selesai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $doc->creator->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('shipping-documents.show', $doc->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('shipping-documents.edit', $doc->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('shipping-documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
