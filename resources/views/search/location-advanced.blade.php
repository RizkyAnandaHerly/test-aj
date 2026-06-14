@extends('layouts.sidebar')
@section('title', 'Pencarian Lokasi Barang')
@section('content')

    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold text-dark mb-0">Pencarian Lokasi Barang (Picking)</h4>
            <p class="text-muted small mb-0">Cari lokasi barang berdasarkan kode rak/palet untuk proses picking</p>
        </div>
    </div>

    {{-- Search Form --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('search.location-advanced') }}" method="GET">
                <h6 class="fw-bold text-secondary text-uppercase small mb-3">Filter Pencarian</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="keyword" class="form-label fw-semibold small">Kata Kunci</label>
                        <input type="text" name="keyword" id="keyword"
                               class="form-control form-control-lg bg-light border-0"
                               placeholder="Cari SKU, nama produk, atau kode rak..."
                               value="{{ $keyword }}">
                    </div>

                    <div class="col-md-3">
                        <label for="warehouse_id" class="form-label fw-semibold small">Gudang</label>
                        <select name="warehouse_id" id="warehouse_id"
                                class="form-select form-select-lg bg-light border-0">
                            <option value="">-- Semua Gudang --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $warehouse_id == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->code }} — {{ $wh->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="zone" class="form-label fw-semibold small">Zona</label>
                        <input type="text" name="zone" id="zone"
                               class="form-control form-control-lg bg-light border-0"
                               placeholder="Contoh: A1, B2..."
                               value="{{ $zone }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Cari
                        </button>
                        <a href="{{ route('search.location-advanced') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Results --}}
    @if($results->isNotEmpty())
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-light border-bottom-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Hasil Pencarian ({{ $results->count() }} hasil)
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Gudang</th>
                                <th>Zona</th>
                                <th>Rak</th>
                                <th>Produk</th>
                                <th>SKU</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $item)
                                <tr>
                                    <td>
                                        <p class="fw-semibold text-dark mb-0">{{ $item->location->warehouse->name ?? 'N/A' }}</p>
                                        <p class="text-muted small mb-0">{{ $item->location->warehouse->code ?? '' }}</p>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->location->zone }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->location->rack_code }}</span>
                                    </td>
                                    <td>
                                        <p class="fw-semibold text-dark mb-0">{{ $item->product->name }}</p>
                                        <p class="text-muted small mb-0">{{ $item->product->sku }}</p>
                                    </td>
                                    <td>
                                        <code class="bg-light p-2 rounded">{{ $item->product->sku }}</code>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success fs-6">{{ $item->qty }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        @if($keyword || $warehouse_id || $zone)
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center mb-4">
                <i class="bi bi-search text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted">Tidak ada hasil pencarian yang sesuai</p>
                <p class="text-muted small">Coba gunakan kata kunci lain atau filter berbeda</p>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center mb-4">
                <i class="bi bi-binoculars text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted">Mulai pencarian dengan memasukkan kata kunci atau filter</p>
            </div>
        @endif
    @endif

@endsection
