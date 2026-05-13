<x-app-layout>
    <x-slot name="header">Pencarian Posisi Barang</x-slot>

    {{-- Search Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('search.index') }}" method="GET">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="q"
                           id="search-input"
                           class="form-control bg-light border-0"
                           placeholder="Cari nama produk, SKU, zona, kode rak, atau nama gudang..."
                           value="{{ $q }}"
                           autofocus>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Cari</button>
                    @if($q)
                        <a href="{{ route('search.index') }}" class="btn btn-light border fw-semibold px-3">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Results --}}
    @if($q !== '')
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0 text-dark">
                    Hasil Pencarian: <span class="text-primary">"{{ $q }}"</span>
                    <span class="text-muted fw-normal small ms-2">{{ $results->count() }} lokasi ditemukan</span>
                </h6>
            </div>

            @if($results->isEmpty())
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-geo-alt fs-1 d-block mb-3 text-secondary"></i>
                    <h6 class="fw-semibold">Tidak ada hasil untuk "<strong>{{ $q }}</strong>"</h6>
                    <p class="small mb-0">Coba kata kunci lain: nama produk, SKU, zona, kode rak, atau nama gudang.</p>
                </div>
            @else
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">Produk</th>
                                    <th class="py-3 text-uppercase small fw-bold text-secondary">Gudang</th>
                                    <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Zona</th>
                                    <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Rak / Palet</th>
                                    <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Lantai</th>
                                    <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $item->product->name }}</div>
                                            <div class="text-muted small">{{ $item->product->sku }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $item->location->warehouse->name ?? '—' }}</div>
                                            <div class="text-muted small">{{ $item->location->warehouse->code ?? '' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3">
                                                Zona {{ $item->location->zone }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-medium">{{ $item->location->rack_code }}</div>
                                            @if($item->location->pallet_code)
                                                <div class="text-muted small">Palet: {{ $item->location->pallet_code }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary rounded-pill">L{{ $item->location->floor_level }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <span class="fw-bold text-primary fs-6">{{ $item->qty_stored }}</span>
                                            <span class="text-muted small d-block">{{ $item->product->unit }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-search fs-1 d-block mb-3 text-secondary"></i>
            <p class="fw-semibold">Masukkan kata kunci untuk mencari posisi barang di gudang.</p>
        </div>
    @endif
</x-app-layout>
