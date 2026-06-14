@extends('layouts.sidebar')
@section('title', 'Daftar Packing & Pelabelan')
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
                    <h4 class="fw-bold mb-1">Daftar Packing & Pelabelan</h4>
                    <p class="text-muted small mb-0">Catatan fisik packing dan kode label produk yang sudah dicetak.</p>
                </div>
                <a href="{{ route('packing-details.create') }}" class="btn btn-success fw-semibold px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Packing
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Label</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Packaging</th>
                                    <th>Packer</th>
                                    <th>Waktu</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packings as $packing)
                                    <tr>
                                        <td>{{ $packing->id }}</td>
                                        <td>{{ $packing->label_code }}</td>
                                        <td>
                                            {{ $packing->product->name ?? '—' }}<br>
                                            <small class="text-muted">{{ $packing->product->sku ?? '—' }}</small>
                                        </td>
                                        <td>{{ number_format($packing->quantity) }}</td>
                                        <td>{{ $packing->packaging_type }}</td>
                                        <td>{{ $packing->packer->name ?? '—' }}</td>
                                        <td>{{ $packing->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('packing-details.show', $packing) }}" class="btn btn-sm btn-outline-primary">
                                                Lihat Label
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Belum ada data packing.
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
