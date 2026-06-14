@extends('layouts.sidebar')
@section('title', 'Riwayat Inbound')
@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Search & Actions Bar ─────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body px-4 py-3">
            <form method="GET" action="{{ route('inbounds.index') }}">
                <div class="row g-2 align-items-end">
                    
                    {{-- Search Input --}}
                    <div class="col-12 col-md-10">
                        <label for="search" class="form-label small fw-semibold text-secondary mb-1">Cari Data Inbound</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-secondary"></i>
                            </span>
                            <input type="text" id="search" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari SKU / nama barang, supplier, atau nomor batch..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                        <a href="{{ route('inbounds.index') }}" class="btn btn-outline-secondary w-100" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- ── Inbound Table Card ────────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Riwayat Penerimaan Barang (Inbound)
                </h5>
                <p class="text-muted small mb-0 mt-1">Daftar pencatatan logistik barang masuk ke gudang</p>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                    {{ $inbounds->total() }} record inbound
                </span>
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
                    <a href="{{ route('inbounds.create') }}" class="btn btn-primary fw-bold px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Input Inbound Baru
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">Tanggal Terima</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Barang</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Supplier / Vendor</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Batch No</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Jumlah (Qty)</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Penerima</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary text-center">Status QC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inbounds as $inbound)
                            <tr>
                                {{-- Tanggal Terima --}}
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $inbound->received_date->format('d/m/Y') }}
                                </td>

                                {{-- Barang (SKU + Nama) --}}
                                <td>
                                    <div class="fw-bold text-dark">{{ $inbound->product->name ?? 'Produk Dihapus' }}</div>
                                    <code class="text-secondary small">{{ $inbound->product->sku ?? '—' }}</code>
                                </td>

                                {{-- Vendor --}}
                                <td>
                                    <div class="text-dark fw-semibold small">{{ $inbound->vendor->name ?? '—' }}</div>
                                    <span class="badge bg-light border text-secondary rounded-pill" style="font-size: 0.65rem;">{{ $inbound->vendor->code ?? '—' }}</span>
                                </td>

                                {{-- Batch No --}}
                                <td class="text-center text-muted small">
                                    {{ $inbound->batch_no ?? '—' }}
                                </td>

                                {{-- Qty --}}
                                <td class="text-center fw-bold text-primary">
                                    {{ number_format($inbound->qty) }} <span class="text-muted small fw-normal">{{ $inbound->product->unit ?? 'pcs' }}</span>
                                </td>

                                {{-- Penerima (User) --}}
                                <td>
                                    <span class="text-dark small">{{ $inbound->receiver->name ?? 'System' }}</span>
                                </td>

                                {{-- Status QC --}}
                                <td class="pe-4 text-center">
                                    @if($inbound->qcInspection)
                                        @if($inbound->qcInspection->status === 'pass')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                                <i class="bi bi-shield-fill-check me-1"></i>QC PASS
                                            </span>
                                        @elseif($inbound->qcInspection->status === 'fail')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                                                <i class="bi bi-shield-fill-x me-1"></i>QC FAIL
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                                {{ strtoupper($inbound->qcInspection->status) }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">
                                            <i class="bi bi-hourglass-split me-1"></i>PENDING
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-arrow-in-down fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    <p class="fw-semibold text-secondary mb-1">Belum ada data barang masuk</p>
                                    <p class="text-muted small mb-0">Silakan catat data barang masuk pertama Anda.</p>
                                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
                                        <a href="{{ route('inbounds.create') }}" class="btn btn-sm btn-outline-primary mt-3 fw-bold">
                                            <i class="bi bi-plus-circle me-1"></i>Input Inbound Pertama
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($inbounds->hasPages())
            <div class="card-footer bg-white border-top px-4 py-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $inbounds->firstItem() }}–{{ $inbounds->lastItem() }}</strong> dari <strong>{{ $inbounds->total() }}</strong> records
                </div>
                <div>
                    {{ $inbounds->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

@endsection
