<x-app-layout>
    <x-slot name="header">
        Riwayat Quality Control (QC)
    </x-slot>

    {{-- ── Flash Success ───────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2"
             role="alert">
            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-shield-check me-2 text-success"></i>Data Inspeksi QC
                </h5>
                <p class="text-muted small mb-0">Riwayat seluruh hasil pemeriksaan kualitas barang masuk</p>
            </div>
            <a href="{{ route('qc-inspections.create') }}" class="btn btn-success fw-bold shadow-sm px-4">
                <i class="bi bi-plus-circle me-2"></i> Input QC Baru
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">Inbound</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Produk</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Inspektor</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Tanggal</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Status</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Parameter</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspections as $inspection)
                            <tr>
                                {{-- Inbound --}}
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark small">
                                        <code>#{{ $inspection->inbound_id }}</code>
                                    </div>
                                    @if($inspection->inbound?->batch_no)
                                        <div class="text-muted" style="font-size:0.75rem">
                                            Batch: {{ $inspection->inbound->batch_no }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Produk --}}
                                <td>
                                    <div class="fw-semibold text-dark">
                                        {{ $inspection->product->name ?? '—' }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $inspection->product->sku ?? '' }}
                                    </div>
                                </td>

                                {{-- Inspektor --}}
                                <td>
                                    <div class="fw-medium text-dark small">
                                        {{ $inspection->inspector->name ?? '—' }}
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="text-center">
                                    <span class="small text-dark">
                                        {{ $inspection->inspection_date?->format('d/m/Y') }}
                                    </span>
                                </td>

                                {{-- Status Badge --}}
                                <td class="text-center">
                                    @php $st = $inspection->status; @endphp

                                    @if($st === 'pass')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i>Lulus
                                        </span>
                                    @elseif($st === 'fail')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                            <i class="bi bi-x-circle-fill me-1"></i>Gagal
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i>Parsial
                                        </span>
                                    @endif
                                </td>

                                {{-- Jumlah Parameter --}}
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3">
                                        {{ $inspection->parameters->count() }} param
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="pe-4 text-center">
                                    <button class="btn btn-sm btn-outline-secondary fw-semibold px-3"
                                            title="Detail (coming soon)" disabled>
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-shield-x fs-1 d-block mb-3 text-muted opacity-50"></i>
                                    <p class="fw-semibold text-secondary mb-1">Belum ada data inspeksi QC</p>
                                    <p class="text-muted small mb-3">
                                        Data akan muncul setelah staf gudang mengisi form QC barang masuk.
                                    </p>
                                    <a href="{{ route('qc-inspections.create') }}"
                                       class="btn btn-sm btn-success px-4 fw-semibold">
                                        <i class="bi bi-plus-circle me-1"></i> Input QC Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Row count footer --}}
        @if($inspections->isNotEmpty())
            <div class="card-footer bg-white border-top px-4 py-2">
                <span class="text-muted small">
                    Menampilkan <strong>{{ $inspections->count() }}</strong> data inspeksi
                </span>
            </div>
        @endif

    </div>
</x-app-layout>
