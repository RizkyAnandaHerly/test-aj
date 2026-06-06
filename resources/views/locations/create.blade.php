@extends('layouts.sidebar')
@section('title', 'Alokasi Lokasi Barang')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                        <i class="bi bi-box-arrow-in-right fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Alokasi Penempatan Barang</h5>
                        <p class="text-muted small mb-0">Assign barang ke lokasi rak atau palet di gudang</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Informasi Barang</h6>

                        <div class="row mb-4">
                            {{-- Pilih Barang --}}
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="product_id" class="form-label fw-semibold small">
                                    Pilih Barang (SKU) <span class="text-danger">*</span>
                                </label>
                                <select name="product_id" id="product_id"
                                        class="form-select form-select-lg bg-light border-0 @error('product_id') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>-- Cari atau Pilih Barang --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->sku }} — {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Kuantitas --}}
                            <div class="col-md-4">
                                <label for="qty_stored" class="form-label fw-semibold small">
                                    Kuantitas Disimpan <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="qty_stored" id="qty_stored" min="1"
                                       class="form-control form-control-lg bg-light border-0 @error('qty_stored') is-invalid @enderror"
                                       placeholder="Contoh: 50"
                                       value="{{ old('qty_stored') }}" required>
                                @error('qty_stored')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2 mt-4">
                            Lokasi Penyimpanan (Cascading)
                        </h6>

                        <div class="row g-3 mb-3">
                            {{-- Gudang --}}
                            <div class="col-md-6">
                                <label for="warehouse_sel" class="form-label fw-semibold small">
                                    Gudang <span class="text-danger">*</span>
                                </label>
                                <select id="warehouse_sel" class="form-select bg-light border-0">
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->code }} — {{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Zona --}}
                            <div class="col-md-6">
                                <label for="zone_sel" class="form-label fw-semibold small">
                                    Zona <span class="text-danger">*</span>
                                </label>
                                <select id="zone_sel" class="form-select bg-light border-0" disabled>
                                    <option value="">-- Pilih Gudang dulu --</option>
                                </select>
                            </div>

                            {{-- Rak --}}
                            <div class="col-md-6">
                                <label for="rack_sel" class="form-label fw-semibold small">
                                    Rak <span class="text-danger">*</span>
                                </label>
                                <select id="rack_sel" class="form-select bg-light border-0" disabled>
                                    <option value="">-- Pilih Zona dulu --</option>
                                </select>
                            </div>

                            {{-- Palet (optional) --}}
                            <div class="col-md-6" id="pallet_wrap" style="display:none;">
                                <label for="pallet_sel" class="form-label fw-semibold small">Palet</label>
                                <select id="pallet_sel" class="form-select bg-light border-0">
                                    <option value="">-- Tanpa Palet (level rak) --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Hidden actual location_id submitted to form --}}
                        <input type="hidden" name="location_id" id="location_id" value="{{ old('location_id') }}">
                        @error('location_id')
                            <div class="text-danger small mb-3">{{ $message }}</div>
                        @enderror

                        {{-- Capacity hint --}}
                        <div id="capacity-info" class="rounded-3 px-3 py-2 small fw-semibold mb-4"
                             style="display:none; background:#f0fdf4; border:1px solid #bbf7d0;">
                            <span id="capacity-text"></span>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('locations.index') }}"
                               class="btn btn-light border px-4 me-2 fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Penempatan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Cascading Dropdown + Capacity Script --}}
    <script>
    (function () {
        const warehouseSel = document.getElementById('warehouse_sel');
        const zoneSel      = document.getElementById('zone_sel');
        const rackSel      = document.getElementById('rack_sel');
        const palletSel    = document.getElementById('pallet_sel');
        const palletWrap   = document.getElementById('pallet_wrap');
        const locationHid  = document.getElementById('location_id');
        const capInfo      = document.getElementById('capacity-info');
        const capText      = document.getElementById('capacity-text');

        function resetSelect(el, placeholder, disabled = true) {
            el.innerHTML = `<option value="">${placeholder}</option>`;
            el.disabled  = disabled;
        }

        function clearLocation() {
            locationHid.value = '';
            capInfo.style.display = 'none';
        }

        function showCapacity(remaining, capacity) {
            const used = capacity - remaining;
            const pct  = capacity > 0 ? Math.round((used / capacity) * 100) : 0;
            capText.textContent = `Kapasitas tersedia: ${remaining} dari ${capacity} (${pct}% terpakai)`;
            capInfo.style.background  = remaining < 20 ? '#fef2f2' : '#f0fdf4';
            capInfo.style.borderColor = remaining < 20 ? '#fecaca' : '#bbf7d0';
            capText.style.color       = remaining < 20 ? '#dc2626' : '#16a34a';
            capInfo.style.display     = 'block';
        }

        // Gudang → Zona
        warehouseSel.addEventListener('change', async function () {
            resetSelect(zoneSel, '-- Pilih Zona --');
            resetSelect(rackSel, '-- Pilih Zona dulu --');
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            clearLocation();
            if (!this.value) return;

            const zones = await fetch(`/api/locations/zones?warehouse_id=${this.value}`).then(r => r.json());
            zones.forEach(z => {
                const o = document.createElement('option');
                o.value = z; o.textContent = `Zona ${z}`;
                zoneSel.appendChild(o);
            });
            zoneSel.disabled = zones.length === 0;
        });

        // Zona → Rak
        zoneSel.addEventListener('change', async function () {
            resetSelect(rackSel, '-- Pilih Rak --');
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            clearLocation();
            if (!this.value) return;

            const racks = await fetch(
                `/api/locations/racks?warehouse_id=${warehouseSel.value}&zone=${this.value}`
            ).then(r => r.json());

            racks.forEach(r => {
                const o = document.createElement('option');
                o.value = r.id;
                o.textContent = `${r.rack_code} (Sisa: ${r.remaining}/${r.capacity})`;
                o.dataset.remaining = r.remaining;
                o.dataset.capacity  = r.capacity;
                o.dataset.rackCode  = r.rack_code;
                rackSel.appendChild(o);
            });
            rackSel.disabled = racks.length === 0;
        });

        // Rak → Palet + set location_id + capacity hint
        rackSel.addEventListener('change', async function () {
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            clearLocation();
            if (!this.value) return;

            // Set location_id to this rack by default (overridden if palet selected)
            locationHid.value = this.value;

            const opt = this.options[this.selectedIndex];
            showCapacity(parseInt(opt.dataset.remaining), parseInt(opt.dataset.capacity));

            const pallets = await fetch(
                `/api/locations/pallets?warehouse_id=${warehouseSel.value}&zone=${zoneSel.value}&rack=${opt.dataset.rackCode}`
            ).then(r => r.json());

            if (pallets.length > 0) {
                pallets.forEach(p => {
                    const o = document.createElement('option');
                    o.value = p.id;
                    o.textContent = `${p.pallet_code} (Sisa: ${p.remaining}/${p.capacity})`;
                    o.dataset.remaining = p.remaining;
                    o.dataset.capacity  = p.capacity;
                    palletSel.appendChild(o);
                });
                palletWrap.style.display = 'block';
            }
        });

        // Palet selected → override location_id + update capacity hint
        palletSel.addEventListener('change', function () {
            if (this.value) {
                locationHid.value = this.value;
                const opt = this.options[this.selectedIndex];
                showCapacity(parseInt(opt.dataset.remaining), parseInt(opt.dataset.capacity));
            } else {
                // Revert to rack
                locationHid.value = rackSel.value;
                const rOpt = rackSel.options[rackSel.selectedIndex];
                showCapacity(parseInt(rOpt.dataset.remaining), parseInt(rOpt.dataset.capacity));
            }
        });
    }());
    </script>
@endsection