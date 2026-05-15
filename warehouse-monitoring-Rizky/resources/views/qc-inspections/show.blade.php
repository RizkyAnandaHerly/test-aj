@extends('layouts.sidebar')
@section('title', 'Detail Inspeksi QC')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Header Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded p-2">
                                <i class="bi bi-clipboard2-check fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ $qcInspection->product->name }}</h5>
                                <span class="text-muted small">SKU: {{ $qcInspection->product->sku }}</span>
                            </div>
                        </div>
                        @php
                            $badgeClass = match($qcInspection->status) {
                                'pass'    => 'bg-success-subtle text-success border-success-subtle',
                                'fail'    => 'bg-danger-subtle text-danger border-danger-subtle',
                                'partial' => 'bg-warning-subtle text-warning border-warning-subtle',
                                default   => 'bg-secondary-subtle text-secondary',
                            };
                            $badgeLabel = match($qcInspection->status) {
                                'pass'    => 'LULUS',
                                'fail'    => 'GAGAL',
                                'partial' => 'SEBAGIAN',
                                default   => strtoupper($qcInspection->status),
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} border rounded-pill px-3 py-2 fs-6 fw-bold">
                            {{ $badgeLabel }}
                        </span>
                    </div>

                    <hr class="my-3">

                    <div class="row g-3 text-sm">
                        <div class="col-md-3">
                            <div class="text-muted small fw-semibold text-uppercase">Tanggal Inspeksi</div>
                            <div class="fw-bold">{{ $qcInspection->inspection_date->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small fw-semibold text-uppercase">Inspektor</div>
                            <div class="fw-bold">{{ $qcInspection->inspector->name ?? '—' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small fw-semibold text-uppercase">Batch Inbound</div>
                            <div class="fw-bold">{{ $qcInspection->inbound->batch_no ?? '—' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small fw-semibold text-uppercase">Vendor</div>
                            <div class="fw-bold">{{ $qcInspection->inbound->vendor->name ?? '—' }}</div>
                        </div>
                    </div>

                    @if($qcInspection->notes)
                        <div class="mt-3 p-3 bg-light rounded-3">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Catatan</div>
                            <div class="text-dark">{{ $qcInspection->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Parameters Table --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-list-check me-2 text-primary"></i>
                        Parameter Inspeksi ({{ $qcInspection->parameters->count() }} parameter)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">#</th>
                                    <th class="py-3 text-uppercase small fw-bold text-secondary">Parameter</th>
                                    <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Ekspektasi</th>
                                    <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Aktual</th>
                                    <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary text-center">Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($qcInspection->parameters as $i => $param)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                                        <td class="fw-semibold">{{ $param->parameter_name }}</td>
                                        <td class="text-center text-muted">{{ $param->expected_value }}</td>
                                        <td class="text-center fw-medium">{{ $param->actual_value }}</td>
                                        <td class="text-center pe-4">
                                            @if($param->result === 'pass')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                                    <i class="bi bi-check-circle me-1"></i>Pass
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                                    <i class="bi bi-x-circle me-1"></i>Fail
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top px-4 py-3">
                    <a href="{{ route('qc-inspections.index') }}" class="btn btn-light border fw-semibold px-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
