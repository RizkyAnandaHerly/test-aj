@extends('layouts.sidebar')
@section('title', 'Form QC Inspeksi')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- ── Flash Error Summary ─────────────────────────────────────── --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pada form:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-success-subtle text-success rounded p-2 me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Inspeksi Inbound Barang</h5>
                        <p class="text-muted small mb-0">Evaluasi kualitas barang yang baru masuk ke gudang</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('qc-inspections.store') }}" method="POST" id="qc-form">
                        @csrf

                        {{-- ── Section 1: Informasi Inbound ───────────────────── --}}
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">
                            Informasi Penerimaan
                        </h6>

                        <div class="row mb-4">
                            {{-- Pilih Inbound --}}
                            <div class="col-md-12 mb-3">
                                <label for="inbound_id" class="form-label fw-semibold small">
                                    Pilih Data Inbound <span class="text-danger">*</span>
                                </label>
                                <select name="inbound_id"
                                        id="inbound_id"
                                        class="form-select form-select-lg bg-light border-0 @error('inbound_id') is-invalid @enderror"
                                        required>
                                    <option value="" selected disabled>-- Pilih Penerimaan Barang --</option>
                                    @foreach($inbounds as $inb)
                                        <option value="{{ $inb->id }}"
                                                data-product-id="{{ $inb->product_id }}"
                                            {{ old('inbound_id') == $inb->id ? 'selected' : '' }}>
                                            #{{ $inb->id }}
                                            — {{ $inb->product->sku ?? '—' }}
                                            @if($inb->batch_no) | Batch: {{ $inb->batch_no }}@endif
                                            | {{ $inb->received_date?->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('inbound_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pilih Produk --}}
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="product_id" class="form-label fw-semibold small">
                                    Produk yang Diinspeksi <span class="text-danger">*</span>
                                </label>
                                <select name="product_id"
                                        id="product_id"
                                        class="form-select form-select-lg border-0 @error('product_id') is-invalid @enderror"
                                        style="pointer-events: none; background-color: #e9ecef;"
                                        required>
                                    <option value="" selected disabled>-- Produk Akan Terisi Otomatis --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->sku }} — {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal Inspeksi --}}
                            <div class="col-md-4">
                                <label for="inspection_date" class="form-label fw-semibold small">
                                    Tanggal Inspeksi <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="inspection_date"
                                       id="inspection_date"
                                       class="form-control form-control-lg bg-light border-0 @error('inspection_date') is-invalid @enderror"
                                       value="{{ old('inspection_date', date('Y-m-d')) }}"
                                       required>
                                @error('inspection_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ── Section 2: Catatan ──────────────────────────────── --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold small">
                                Catatan Kerusakan / Temuan
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="3"
                                      class="form-control bg-light border-0 @error('notes') is-invalid @enderror"
                                      placeholder="Deskripsikan jika ada barang cacat atau tidak sesuai parameter...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ── Section 3: Parameter Inspeksi ──────────────────── --}}
                        <div class="mb-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold text-secondary text-uppercase small mb-0">
                                        Parameter Inspeksi
                                    </h6>
                                    <span class="text-muted" style="font-size:0.75rem">
                                        Minimal 1 parameter wajib diisi
                                    </span>
                                </div>
                                <button type="button"
                                        id="btn-add-row"
                                        class="btn btn-sm btn-outline-primary rounded-pill fw-semibold px-3">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Parameter
                                </button>
                            </div>

                            @error('parameter_name')
                                <div class="alert alert-danger py-2 px-3 small mb-3 rounded-3">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="table-responsive">
                                <table class="table align-middle mb-0 border rounded-3 overflow-hidden"
                                       id="param-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3 py-2 small fw-bold text-secondary text-uppercase" style="width:28%">
                                                Parameter
                                            </th>
                                            <th class="py-2 small fw-bold text-secondary text-uppercase" style="width:22%">
                                                Nilai Ekspektasi
                                            </th>
                                            <th class="py-2 small fw-bold text-secondary text-uppercase" style="width:22%">
                                                Nilai Aktual
                                            </th>
                                            <th class="py-2 small fw-bold text-secondary text-uppercase text-center" style="width:16%">
                                                Hasil
                                            </th>
                                            <th class="pe-3 py-2 text-center" style="width:12%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="param-body">
                                        {{-- Default row — repopulated by old() if validation fails --}}
                                        @php
                                            $oldNames    = old('parameter_name', ['']);
                                            $oldExpected = old('expected_value',  ['']);
                                            $oldActual   = old('actual_value',    ['']);
                                            $oldResult   = old('result',          ['pass']);
                                        @endphp

                                        @foreach($oldNames as $i => $oldName)
                                        <tr class="param-row">
                                            <td class="ps-3 py-2">
                                                <input type="text"
                                                       name="parameter_name[]"
                                                       class="form-control form-control-sm bg-light border-0"
                                                       placeholder="Cth: Berat, Kelembaban..."
                                                       value="{{ $oldName }}"
                                                       required>
                                            </td>
                                            <td class="py-2">
                                                <input type="text"
                                                       name="expected_value[]"
                                                       class="form-control form-control-sm bg-light border-0"
                                                       placeholder="Cth: ≤ 13%"
                                                       value="{{ $oldExpected[$i] ?? '' }}"
                                                       required>
                                            </td>
                                            <td class="py-2">
                                                <input type="text"
                                                       name="actual_value[]"
                                                       class="form-control form-control-sm bg-light border-0"
                                                       placeholder="Cth: 12.5%"
                                                       value="{{ $oldActual[$i] ?? '' }}"
                                                       required>
                                            </td>
                                            <td class="py-2 text-center">
                                                <select name="result[]"
                                                        class="form-select form-select-sm bg-light border-0 result-select">
                                                    <option value="pass" {{ ($oldResult[$i] ?? 'pass') === 'pass' ? 'selected' : '' }}>
                                                        ✅ Pass
                                                    </option>
                                                    <option value="fail" {{ ($oldResult[$i] ?? '') === 'fail' ? 'selected' : '' }}>
                                                        ❌ Fail
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="pe-3 py-2 text-center">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger hapus-row"
                                                        title="Hapus baris">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Auto-status info --}}
                            <div class="mt-3 px-3 py-2 rounded-3 bg-light border small text-secondary d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle-fill text-primary flex-shrink-0"></i>
                                <span>
                                    Status inspeksi akan ditentukan otomatis berdasarkan hasil parameter.
                                    Semua <em>pass</em> → Lulus · Semua <em>fail</em> → Gagal · Campuran → Parsial.
                                </span>
                            </div>
                        </div>

                        {{-- ── Tombol Aksi ─────────────────────────────────────── --}}
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('qc-inspections.index') }}"
                               class="btn btn-light border px-4 me-2 fw-semibold">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">
                                <i class="bi bi-clipboard-check me-1"></i> Submit Hasil QC
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Vanilla JS: Dynamic Parameter Rows & Product Automation ───────── --}}
    <script>
    (function () {
        // --- Bagian Dinamis Baris Parameter (Kode Lama) ---
        const tbody    = document.getElementById('param-body');
        const addBtn   = document.getElementById('btn-add-row');

        function newRow() {
            const tr = document.createElement('tr');
            tr.className = 'param-row';
            tr.innerHTML = `
                <td class="ps-3 py-2">
                    <input type="text" name="parameter_name[]"
                           class="form-control form-control-sm bg-light border-0"
                           placeholder="Cth: Berat, Kelembaban..." required>
                </td>
                <td class="py-2">
                    <input type="text" name="expected_value[]"
                           class="form-control form-control-sm bg-light border-0"
                           placeholder="Cth: ≤ 13%" required>
                </td>
                <td class="py-2">
                    <input type="text" name="actual_value[]"
                           class="form-control form-control-sm bg-light border-0"
                           placeholder="Cth: 12.5%" required>
                </td>
                <td class="py-2 text-center">
                    <select name="result[]"
                            class="form-select form-select-sm bg-light border-0 result-select">
                        <option value="pass">✅ Pass</option>
                        <option value="fail">❌ Fail</option>
                    </select>
                </td>
                <td class="pe-3 py-2 text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger hapus-row" title="Hapus baris">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            return tr;
        }

        function syncHapusState() {
            const rows    = tbody.querySelectorAll('.param-row');
            const disable = rows.length <= 1;
            rows.forEach(row => {
                const btn = row.querySelector('.hapus-row');
                btn.disabled = disable;
                btn.classList.toggle('opacity-50', disable);
            });
        }

        addBtn.addEventListener('click', function () {
            tbody.appendChild(newRow());
            syncHapusState();
            tbody.lastElementChild.querySelector('input').focus();
        });

        tbody.addEventListener('click', function (e) {
            const btn = e.target.closest('.hapus-row');
            if (!btn || btn.disabled) return;
            btn.closest('.param-row').remove();
            syncHapusState();
        });

        syncHapusState();

        // --- PERBAIKAN: Bagian Otomasi Dropdown Produk (Kode Baru) ---
        const inboundSelect = document.getElementById('inbound_id');
        const productSelect = document.getElementById('product_id');

        if (inboundSelect && productSelect) {
            inboundSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const productId = selectedOption.getAttribute('data-product-id');

                if (productId) {
                    productSelect.value = productId;
                } else {
                    productSelect.value = '';
                }
            });

            if(inboundSelect.value !== "") {
                inboundSelect.dispatchEvent(new Event('change'));
            }
        }
    }());
    </script>

@endsection