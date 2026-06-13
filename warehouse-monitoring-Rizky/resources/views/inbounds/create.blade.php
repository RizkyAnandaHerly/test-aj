@extends('layouts.sidebar')
@section('title', 'Input Inbound')

@section('styles')
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .ts-wrapper.form-select-lg .ts-control {
        min-height: 48px !important;
        padding: 0.5rem 1rem !important;
        font-size: 1rem !important;
    }
    .ts-control {
        border: 0 !important;
        background-color: #f8fafc !important; /* match bg-light */
        border-radius: 0.5rem !important;
        box-shadow: none !important;
    }
    .ts-dropdown {
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.05) !important;
        border: 1px solid #e2e8f0 !important;
        padding: 0.25rem !important;
    }
    .ts-dropdown .active {
        background-color: #3b82f6 !important;
        color: white !important;
        border-radius: 0.25rem !important;
    }
</style>
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
                    <span class="fw-semibold">{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                        <i class="bi bi-box-arrow-in-down fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Form Inbound Barang</h5>
                        <p class="text-muted small mb-0">Catat penerimaan barang baru dari vendor ke dalam sistem</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('inbounds.store') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Detail Penerimaan</h6>

                        {{-- Pilih Produk --}}
                        <div class="mb-4">
                            <label for="product_id" class="form-label fw-semibold small">
                                Pilih Produk <span class="text-danger">*</span>
                            </label>
                            <select name="product_id" id="product_id"
                                    class="form-select form-select-lg bg-light border-0 @error('product_id') is-invalid @enderror"
                                    required>
                                <option value="" selected disabled>-- Cari atau Pilih Produk --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->sku }} — {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Pilih Vendor --}}
                        <div class="mb-4">
                            <label for="vendor_id" class="form-label fw-semibold small">
                                Vendor / Supplier <span class="text-danger">*</span>
                            </label>
                            <select name="vendor_id" id="vendor_id"
                                    class="form-select form-select-lg bg-light border-0 @error('vendor_id') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Vendor --</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>
                                        {{ $v->code }} — {{ $v->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vendor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Kuantitas & Batch --}}
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="qty" class="form-label fw-semibold small">
                                    Kuantitas Diterima <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="qty" id="qty" min="1"
                                       class="form-control form-control-lg bg-light border-0 @error('qty') is-invalid @enderror"
                                       placeholder="Contoh: 150"
                                       value="{{ old('qty') }}" required>
                                @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="batch_no" class="form-label fw-semibold small">Nomor Batch</label>
                                <input type="text" name="batch_no" id="batch_no"
                                       class="form-control form-control-lg bg-light border-0 @error('batch_no') is-invalid @enderror"
                                       placeholder="Contoh: BATCH-2026-05A"
                                       value="{{ old('batch_no') }}">
                                @error('batch_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Tanggal Penerimaan --}}
                        <div class="mb-4">
                            <label for="received_date" class="form-label fw-semibold small">
                                Tanggal Penerimaan <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="received_date" id="received_date"
                                   class="form-control form-control-lg bg-light border-0 @error('received_date') is-invalid @enderror"
                                   value="{{ old('received_date', date('Y-m-d')) }}" required>
                            @error('received_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Lokasi Bertingkat (Cascading) --}}
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2 mt-2">Lokasi Penempatan (Opsional)</h6>

                        <div class="row mb-3 g-3">
                            {{-- Gudang --}}
                            <div class="col-md-6">
                                <label for="warehouse_sel" class="form-label fw-semibold small">Gudang</label>
                                <select id="warehouse_sel"
                                        class="form-select bg-light border-0">
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->code }} — {{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Zona --}}
                            <div class="col-md-6">
                                <label for="zone_sel" class="form-label fw-semibold small">Zona</label>
                                <select id="zone_sel" class="form-select bg-light border-0" disabled>
                                    <option value="">-- Pilih Gudang dulu --</option>
                                </select>
                            </div>

                            {{-- Rak --}}
                            <div class="col-md-6">
                                <label for="rack_sel" class="form-label fw-semibold small">Rak</label>
                                <select id="rack_sel" class="form-select bg-light border-0" disabled>
                                    <option value="">-- Pilih Zona dulu --</option>
                                </select>
                            </div>

                            {{-- Palet (hidden input resolved from selection) --}}
                            <div class="col-md-6" id="pallet_wrap" style="display:none;">
                                <label for="pallet_sel" class="form-label fw-semibold small">Palet</label>
                                <select id="pallet_sel" class="form-select bg-light border-0">
                                    <option value="">-- Tanpa Palet --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Capacity hint --}}
                        <div id="capacity-info" class="mb-4 rounded-3 px-3 py-2 small fw-semibold" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0;">
                            <span id="capacity-text"></span>
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold small">Catatan Penerimaan</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="form-control bg-light border-0 @error('notes') is-invalid @enderror"
                                      placeholder="Keterangan kondisi fisik barang saat tiba...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Aksi --}}
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-light border px-4 me-2 fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Data Inbound
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#product_id', {
            create: false,
            placeholder: '-- Cari atau Pilih Produk --',
            controlInput: '<input>'
        });
        new TomSelect('#vendor_id', {
            create: false,
            placeholder: '-- Pilih Vendor --',
            controlInput: '<input>'
        });
    });
    </script>

    {{-- Cascading Dropdown Script --}}
    <script>
    (function () {
        const warehouseSel = document.getElementById('warehouse_sel');
        const zoneSel      = document.getElementById('zone_sel');
        const rackSel      = document.getElementById('rack_sel');
        const palletSel    = document.getElementById('pallet_sel');
        const palletWrap   = document.getElementById('pallet_wrap');
        const capInfo      = document.getElementById('capacity-info');
        const capText      = document.getElementById('capacity-text');

        function resetSelect(el, placeholder, disabled = true) {
            el.innerHTML = `<option value="">${placeholder}</option>`;
            el.disabled  = disabled;
        }

        function showCapacity(remaining, capacity) {
            const used = capacity - remaining;
            const pct  = capacity > 0 ? Math.round((used / capacity) * 100) : 0;
            capText.textContent = `Kapasitas tersedia: ${remaining} dari ${capacity} (${pct}% terpakai)`;
            capInfo.style.background   = remaining < 20 ? '#fef2f2' : '#f0fdf4';
            capInfo.style.borderColor  = remaining < 20 ? '#fecaca' : '#bbf7d0';
            capText.style.color        = remaining < 20 ? '#dc2626' : '#16a34a';
            capInfo.style.display      = 'block';
        }

        // Gudang → Zona
        warehouseSel.addEventListener('change', async function () {
            resetSelect(zoneSel, '-- Pilih Zona --');
            resetSelect(rackSel, '-- Pilih Zona dulu --');
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            capInfo.style.display    = 'none';

            if (!this.value) return;

            const res   = await fetch(`/api/locations/zones?warehouse_id=${this.value}`);
            const zones = await res.json();

            zones.forEach(z => {
                const opt = document.createElement('option');
                opt.value = z; opt.textContent = `Zona ${z}`;
                zoneSel.appendChild(opt);
            });
            zoneSel.disabled = zones.length === 0;
        });

        // Zona → Rak
        zoneSel.addEventListener('change', async function () {
            resetSelect(rackSel, '-- Pilih Rak --');
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            capInfo.style.display    = 'none';

            if (!this.value) return;

            const whId = warehouseSel.value;
            const res  = await fetch(`/api/locations/racks?warehouse_id=${whId}&zone=${this.value}`);
            const racks = await res.json();

            racks.forEach(r => {
                const opt = document.createElement('option');
                opt.value       = r.id;
                opt.textContent = `${r.rack_code} (Sisa: ${r.remaining}/${r.capacity})`;
                opt.dataset.remaining = r.remaining;
                opt.dataset.capacity  = r.capacity;
                rackSel.appendChild(opt);
            });
            rackSel.disabled = racks.length === 0;
        });

        // Rak → Palet + kapasitas hint
        rackSel.addEventListener('change', async function () {
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            capInfo.style.display    = 'none';

            if (!this.value) return;

            // Show capacity for this rack
            const opt = this.options[this.selectedIndex];
            if (opt.dataset.remaining !== undefined) {
                showCapacity(parseInt(opt.dataset.remaining), parseInt(opt.dataset.capacity));
            }

            const whId = warehouseSel.value;
            const zone = zoneSel.value;
            const rack = opt.textContent.split(' ')[0]; // Extract rack_code
            const res  = await fetch(`/api/locations/pallets?warehouse_id=${whId}&zone=${zone}&rack=${rack}`);
            const pallets = await res.json();

            if (pallets.length > 0) {
                pallets.forEach(p => {
                    const o = document.createElement('option');
                    o.value = p.id;
                    o.textContent = `${p.pallet_code} (Sisa: ${p.remaining}/${p.capacity})`;
                    palletSel.appendChild(o);
                });
                palletWrap.style.display = 'block';
            }
        });
    }());
    </script>
@endsection