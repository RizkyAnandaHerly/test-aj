@extends('layouts.sidebar')
@section('title', 'Katalog Barang')
@section('content')


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Search & Filter Bar ─────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body px-4 py-3">
            <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                <div class="row g-2 align-items-end">

                    {{-- Search input --}}
                    <div class="col-12 col-md-5">
                        <label for="search" class="form-label small fw-semibold text-secondary mb-1">
                            Cari Barang
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-secondary"></i>
                            </span>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control border-start-0 ps-0"
                                placeholder="Cari nama / SKU..."
                                value="{{ request('search') }}"
                            >
                        </div>
                    </div>

                    {{-- Filter Status --}}
                    <div class="col-6 col-md-3">
                        <label for="status" class="form-label small fw-semibold text-secondary mb-1">
                            Status
                        </label>
                        <select id="status" name="status" class="form-select">
                            <option value="">— Semua Status —</option>
                            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    {{-- Filter Kategori --}}
                    <div class="col-6 col-md-3">
                        <label for="category" class="form-label small fw-semibold text-secondary mb-1">
                            Kategori
                        </label>
                        <select id="category" name="category" class="form-select">
                            <option value="">— Semua Kategori —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Cari + Reset --}}
                    <div class="col-12 col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold" title="Cari">
                            <i class="bi bi-funnel-fill"></i>
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- ── Products Table ───────────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-boxes me-2 text-primary"></i>Daftar Produk Master
            </h5>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                    {{ $products->total() }} barang ditemukan
                </span>
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
                    <a href="{{ route('products.create') }}" class="btn btn-primary fw-bold px-4 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Barang
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary" style="width:110px">SKU</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Nama Barang</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Kategori</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Satuan</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Stok</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Min Stok</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Status</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="{{ $product->isLowStock() ? 'table-warning' : '' }}">

                                {{-- SKU --}}
                                <td class="ps-4">
                                    <code class="text-dark fw-semibold small">{{ $product->sku }}</code>
                                </td>

                                {{-- Nama + Deskripsi --}}
                                <td>
                                    <div class="fw-semibold text-dark">{{ $product->name }}</div>
                                    @if($product->description)
                                        <div class="text-muted small text-truncate" style="max-width:260px">
                                            {{ $product->description }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Kategori --}}
                                <td>
                                    <span class="badge bg-light border text-secondary rounded-pill px-3">
                                        {{ $product->category }}
                                    </span>
                                </td>

                                {{-- Satuan --}}
                                <td class="text-muted small">{{ $product->unit }}</td>

                                {{-- Stok --}}
                                <td class="text-center">
                                    @if($product->isLowStock())
                                        <span class="fw-bold text-danger">{{ $product->stock_qty }}</span>
                                        <i class="bi bi-exclamation-triangle-fill text-warning ms-1"
                                           title="Stok di bawah batas minimum!"></i>
                                    @else
                                        <span class="fw-semibold text-dark">{{ $product->stock_qty }}</span>
                                    @endif
                                </td>

                                {{-- Min Stok --}}
                                <td class="text-center text-muted small">{{ $product->min_stock }}</td>

                                {{-- Status Badge --}}
                                <td class="text-center">
                                    @if(strtolower($product->status) === 'active')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                            <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                            <i class="bi bi-x-circle-fill me-1"></i>Non-Aktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="pe-4 text-center">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('products.show', $product) }}"
                                           class="btn btn-sm btn-light border shadow-sm" title="Detail">
                                            <i class="bi bi-eye-fill text-primary"></i>
                                        </a>
                                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
                                            <a href="{{ route('products.edit', $product) }}"
                                               class="btn btn-sm btn-light border shadow-sm" title="Edit">
                                                <i class="bi bi-pencil-square text-warning-emphasis"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang {{ $product->name }}?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border shadow-sm" title="Hapus">
                                                    <i class="bi bi-trash-fill text-danger"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-box2-open fs-1 d-block mb-3 text-muted opacity-50"></i>
                                    <p class="fw-semibold text-secondary mb-1">Tidak ada produk ditemukan</p>
                                    <p class="text-muted small mb-0">
                                        Coba ubah kata kunci pencarian atau hapus filter yang aktif.
                                    </p>
                                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Pagination ──────────────────────────────────────────────────── --}}
        @if($products->hasPages())
            <div class="card-footer bg-white border-top px-4 py-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan
                    <strong>{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong>
                    dari <strong>{{ $products->total() }}</strong> barang
                </div>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

    </div>

@endsection