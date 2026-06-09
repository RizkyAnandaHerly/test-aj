@extends('layouts.sidebar')

@section('title', 'Manager Dashboard')

@section('styles')
<style>
    .bar-chart-wrap {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 120px;
        padding: 0 4px;
    }
    .bar-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        height: 100%;
        justify-content: flex-end;
    }
    .bar-val {
        font-size: .62rem;
        font-weight: 700;
        color: #1e40af;
    }
    .bar-fill {
        width: 100%;
        background: linear-gradient(180deg,#3b82f6,#1d4ed8);
        border-radius: 4px 4px 0 0;
        min-height: 4px;
        transition: height .3s;
    }
    .bar-label {
        font-size: .62rem;
        color: #94a3b8;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')

{{-- ── Header ───────────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Dashboard Manajer Gudang</h4>
        <p class="text-muted mb-0 small">
            Periode: <strong>{{ now()->translatedFormat('F Y') }}</strong>
            &nbsp;·&nbsp; Diperbarui: {{ now()->format('H:i') }} WIB
        </p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#dbeafe;color:#1e40af;font-size:.75rem;font-weight:600;">
        <i class="bi bi-bar-chart-fill me-1"></i> Manajer Gudang
    </span>
</div>

{{-- ── KPI Cards ────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Card 1: Total Inbound --}}
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;background:#dbeafe;font-size:1.1rem;flex-shrink:0;">📦</div>
                    <span class="text-muted small fw-semibold">Inbound Bulan Ini</span>
                </div>
                <div class="fw-bold mb-0" style="font-size:1.8rem;color:#1e40af;line-height:1.1;">
                    {{ number_format($inboundThisMonth) }}
                </div>
                <div class="text-muted" style="font-size:.75rem;margin-top:2px;">transaksi</div>
                <div class="fw-semibold mt-1" style="font-size:.75rem;color:#3b82f6;">
                    {{ number_format($inboundQtyThisMonth) }} unit masuk
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: QC Pass Rate --}}
    @php
        $qcTotal = $qcPass + $qcFail + $qcPartial;
        $qcRate  = $qcTotal > 0 ? round(($qcPass / $qcTotal) * 100) : 0;
        $qcColor = $qcRate >= 80 ? '#16a34a' : ($qcRate >= 60 ? '#ca8a04' : '#dc2626');
        $qcBg    = $qcRate >= 80 ? '#dcfce7' : ($qcRate >= 60 ? '#fef9c3' : '#fee2e2');
    @endphp
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;background:{{ $qcBg }};font-size:1.1rem;flex-shrink:0;">✅</div>
                    <span class="text-muted small fw-semibold">QC Pass Rate</span>
                </div>
                <div class="fw-bold mb-0" style="font-size:1.8rem;color:{{ $qcColor }};line-height:1.1;">
                    {{ $qcRate }}%
                </div>
                <div style="font-size:.7rem;color:#64748b;margin-top:3px;">
                    <span style="color:#16a34a;">✓ {{ $qcPass }} pass</span> ·
                    <span style="color:#ca8a04;">~ {{ $qcPartial }} partial</span> ·
                    <span style="color:#dc2626;">✗ {{ $qcFail }} fail</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Stok Minimum --}}
    @php $stockCount = $lowStockProducts->count(); @endphp
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3 {{ $stockCount > 0 ? 'border border-danger' : '' }}">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;background:{{ $stockCount > 0 ? '#fee2e2' : '#dcfce7' }};font-size:1.1rem;flex-shrink:0;">
                        {{ $stockCount > 0 ? '⚠️' : '✅' }}
                    </div>
                    <span class="text-muted small fw-semibold">Stok Di Bawah Min</span>
                </div>
                <div class="fw-bold mb-0" style="font-size:1.8rem;color:{{ $stockCount > 0 ? '#dc2626' : '#16a34a' }};line-height:1.1;">
                    {{ $stockCount }}
                </div>
                <div class="text-muted" style="font-size:.75rem;margin-top:2px;">
                    {{ $stockCount > 0 ? 'produk perlu restock' : 'Semua stok aman' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Kapasitas Gudang --}}
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:38px;height:38px;background:#ffedd5;font-size:1.1rem;flex-shrink:0;">🏭</div>
                    <span class="text-muted small fw-semibold">Kapasitas Gudang</span>
                </div>
                <div class="fw-bold mb-0" style="font-size:1.8rem;color:#c2410c;line-height:1.1;">
                    {{ number_format($locationStats['full']) }}
                </div>
                <div class="text-muted" style="font-size:.75rem;margin-top:2px;">lokasi penuh</div>
                <div style="font-size:.75rem;color:#16a34a;margin-top:2px;">
                    {{ number_format($locationStats['available']) }} tersedia
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Bar Chart: Inbound 7 Hari Terakhir ─────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-bar-chart-line-fill text-primary me-2"></i>
            Pergerakan Inbound — 7 Hari Terakhir
        </h6>
    </div>
    <div class="card-body px-4 pb-4 pt-3">
        @php
            // Build a 7-day date map
            $days = collect();
            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i)->format('Y-m-d');
                $days[$d] = 0;
            }
            foreach ($chartData as $row) {
                $days[$row->date] = (int) $row->total;
            }
            $maxVal = max($days->values()->toArray()) ?: 1;
        @endphp

        <div class="bar-chart-wrap">
            @foreach($days as $date => $qty)
                <div class="bar-col">
                    <div class="bar-val">{{ $qty > 0 ? number_format($qty) : '' }}</div>
                    <div class="bar-fill" style="height: {{ max(4, round(($qty / $maxVal) * 100)) }}px;"></div>
                    <div class="bar-label">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between mt-2">
            <span class="small text-muted">Total 7 hari: <strong>{{ number_format($days->sum()) }}</strong> unit</span>
            <span class="small" style="color:#3b82f6;">Puncak: <strong>{{ number_format($maxVal) }}</strong> unit/hari</span>
        </div>
    </div>
</div>

{{-- ── Two Column: Low Stock + Location Stats ───────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Low Stock Table --}}
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    Produk Di Bawah Stok Minimum
                </h6>
            </div>
            <div class="card-body p-0">
                @if($lowStockProducts->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead style="background:#fff5f5;">
                                <tr>
                                    <th class="ps-4 py-2 text-uppercase fw-bold" style="font-size:.62rem;color:#991b1b;">SKU</th>
                                    <th class="py-2 text-uppercase fw-bold" style="font-size:.62rem;color:#991b1b;">Nama Produk</th>
                                    <th class="py-2 text-uppercase fw-bold text-center" style="font-size:.62rem;color:#991b1b;">Stok</th>
                                    <th class="py-2 text-uppercase fw-bold text-center" style="font-size:.62rem;color:#991b1b;">Min</th>
                                    <th class="pe-4 py-2 text-uppercase fw-bold text-center" style="font-size:.62rem;color:#991b1b;">Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $p)
                                    <tr style="background:#fff5f5;">
                                        <td class="ps-4 py-2">
                                            <code style="color:#991b1b;font-size:.75rem;">{{ $p->sku }}</code>
                                        </td>
                                        <td class="py-2 fw-semibold text-dark" style="font-size:.8rem;">
                                            {{ Str::limit($p->name, 28) }}
                                        </td>
                                        <td class="py-2 text-center fw-bold" style="color:#dc2626;">
                                            {{ number_format($p->stock_qty) }}
                                        </td>
                                        <td class="py-2 text-center text-muted">
                                            {{ number_format($p->min_stock) }}
                                        </td>
                                        <td class="pe-4 py-2 text-center">
                                            <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-size:.65rem;">
                                                −{{ number_format($p->min_stock - $p->stock_qty) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
                        <div style="font-size:2rem;margin-bottom:8px;">✅</div>
                        <div class="fw-semibold text-success">Semua produk stok aman</div>
                        <div class="text-muted small">Tidak ada produk di bawah batas minimum saat ini</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Location Stats --}}
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-geo-alt-fill text-warning me-2"></i>Status Kapasitas Lokasi
                </h6>
            </div>
            <div class="card-body px-4 py-4">
                @php
                    $locTotal = array_sum($locationStats);
                    $locTotal = $locTotal ?: 1;
                @endphp

                {{-- Tersedia --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold" style="color:#16a34a;">
                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i> Tersedia
                        </span>
                        <span class="fw-bold small" style="color:#16a34a;">{{ $locationStats['available'] }}</span>
                    </div>
                    <div class="progress rounded-pill" style="height:8px;">
                        <div class="progress-bar"
                             style="width:{{ round(($locationStats['available']/$locTotal)*100) }}%;background:#16a34a;border-radius:20px;"></div>
                    </div>
                </div>

                {{-- Reserved --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold" style="color:#ca8a04;">
                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i> Terisi Sebagian (Reserved)
                        </span>
                        <span class="fw-bold small" style="color:#ca8a04;">{{ $locationStats['reserved'] }}</span>
                    </div>
                    <div class="progress rounded-pill" style="height:8px;">
                        <div class="progress-bar"
                             style="width:{{ round(($locationStats['reserved']/$locTotal)*100) }}%;background:#ca8a04;border-radius:20px;"></div>
                    </div>
                </div>

                {{-- Penuh --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold" style="color:#dc2626;">
                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i> Penuh
                        </span>
                        <span class="fw-bold small" style="color:#dc2626;">{{ $locationStats['full'] }}</span>
                    </div>
                    <div class="progress rounded-pill" style="height:8px;">
                        <div class="progress-bar"
                             style="width:{{ round(($locationStats['full']/$locTotal)*100) }}%;background:#dc2626;border-radius:20px;"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-around text-center pt-2"
                     style="border-top:1px solid #f1f5f9;">
                    <div>
                        <div class="fw-bold" style="font-size:1.2rem;color:#1e293b;">{{ $locTotal }}</div>
                        <div class="text-muted" style="font-size:.7rem;">Total Lokasi</div>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:1.2rem;color:#16a34a;">{{ $locationStats['available'] }}</div>
                        <div class="text-muted" style="font-size:.7rem;">Tersedia</div>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:1.2rem;color:#dc2626;">{{ $locationStats['full'] }}</div>
                        <div class="text-muted" style="font-size:.7rem;">Penuh</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Top 5 Produk Inbound Bulan Ini ───────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-trophy-fill text-warning me-2"></i>
            Top 5 Produk Inbound — {{ now()->translatedFormat('F Y') }}
        </h6>
    </div>
    <div class="card-body p-0">
        @if($topProducts->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="ps-4 py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.62rem;width:50px;">Rank</th>
                            <th class="py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Nama Produk</th>
                            <th class="py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">SKU</th>
                            <th class="pe-4 py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.62rem;">Total Qty Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $i => $row)
                            <tr>
                                <td class="ps-4 py-2 text-center">
                                    @if($i === 0)
                                        <span style="font-size:1.1rem;">🥇</span>
                                    @elseif($i === 1)
                                        <span style="font-size:1.1rem;">🥈</span>
                                    @elseif($i === 2)
                                        <span style="font-size:1.1rem;">🥉</span>
                                    @else
                                        <span class="text-muted fw-bold">#{{ $i + 1 }}</span>
                                    @endif
                                </td>
                                <td class="py-2 fw-semibold text-dark" style="font-size:.82rem;">
                                    {{ $row->product?->name ?? '—' }}
                                </td>
                                <td class="py-2">
                                    <code class="text-muted" style="font-size:.75rem;">{{ $row->product?->sku ?? '—' }}</code>
                                </td>
                                <td class="pe-4 py-2 text-center">
                                    <span class="badge px-3 py-1 rounded-pill"
                                          style="background:#dbeafe;color:#1e40af;font-size:.75rem;font-weight:700;">
                                        {{ number_format($row->total_qty) }} unit
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-muted small">
                <i class="bi bi-inbox fs-4 d-block mb-2 opacity-50"></i>
                Belum ada data inbound bulan ini
            </div>
        @endif
    </div>
</div>

{{-- ── QC Summary Bulan Ini ─────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-shield-check text-success me-2"></i>
            QC Summary — {{ now()->translatedFormat('F Y') }}
        </h6>
    </div>
    <div class="card-body px-4 pt-4 pb-3">
        {{-- 3 big number cards --}}
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="text-center p-3 rounded-3" style="background:#dcfce7;">
                    <div class="fw-bold" style="font-size:2rem;color:#16a34a;line-height:1;">{{ $qcPass }}</div>
                    <div class="fw-semibold mt-1" style="font-size:.8rem;color:#15803d;">
                        <i class="bi bi-check-circle-fill me-1"></i>PASS
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center p-3 rounded-3" style="background:#fef9c3;">
                    <div class="fw-bold" style="font-size:2rem;color:#ca8a04;line-height:1;">{{ $qcPartial }}</div>
                    <div class="fw-semibold mt-1" style="font-size:.8rem;color:#a16207;">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>PARTIAL
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center p-3 rounded-3" style="background:#fee2e2;">
                    <div class="fw-bold" style="font-size:2rem;color:#dc2626;line-height:1;">{{ $qcFail }}</div>
                    <div class="fw-semibold mt-1" style="font-size:.8rem;color:#991b1b;">
                        <i class="bi bi-x-circle-fill me-1"></i>FAIL
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent 5 QC --}}
        <h6 class="fw-bold text-dark mb-2" style="font-size:.8rem;">5 Inspeksi QC Terbaru</h6>
        @if($recentQC->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="ps-3 py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Inbound</th>
                            <th class="py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Produk</th>
                            <th class="py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Inspektor</th>
                            <th class="py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.62rem;">Tanggal</th>
                            <th class="pe-3 py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.62rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentQC as $qc)
                            <tr>
                                <td class="ps-3 py-2">
                                    <code class="text-muted" style="font-size:.75rem;">#{{ $qc->inbound_id }}</code>
                                </td>
                                <td class="py-2 fw-semibold text-dark" style="font-size:.8rem;">
                                    {{ Str::limit($qc->product?->name ?? '—', 24) }}
                                </td>
                                <td class="py-2 text-muted" style="font-size:.78rem;">
                                    {{ $qc->inspector?->name ?? '—' }}
                                </td>
                                <td class="py-2 text-center text-muted" style="font-size:.75rem;">
                                    {{ $qc->inspection_date?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="pe-3 py-2 text-center">
                                    @if($qc->status === 'pass')
                                        <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-size:.65rem;">Lulus</span>
                                    @elseif($qc->status === 'fail')
                                        <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-size:.65rem;">Gagal</span>
                                    @else
                                        <span class="badge rounded-pill" style="background:#fef9c3;color:#854d0e;font-size:.65rem;">Parsial</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-3 text-muted small">Belum ada data QC bulan ini</div>
        @endif
    </div>
</div>

{{-- ── Coming Soon Placeholders ─────────────────────────────────────────── --}}
<div class="row g-3">
    <div class="col-12 col-md-6">
        <a href="{{ route('reports.movements.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="transition:transform .2s,box-shadow .2s;">
                <div class="card-body p-4 text-center">
                    <div style="font-size:1.8rem;margin-bottom:8px;">📊</div>
                    <div class="fw-bold text-dark mb-1">Laporan & Export</div>
                    <div class="text-muted small mb-2">Filter & unduh data ke Excel / CSV / PDF</div>
                    <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-size:.7rem;font-weight:700;">
                        <i class="bi bi-check-circle-fill me-1"></i>Tersedia
                    </span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm rounded-3" style="opacity:.65;">
            <div class="card-body p-4 text-center">
                <div style="font-size:1.8rem;margin-bottom:8px;">📋</div>
                <div class="fw-bold text-dark mb-1">Activity Log</div>
                <div class="text-muted small mb-2">Rekam jejak seluruh aktivitas tim</div>
                <span class="badge rounded-pill" style="background:#e2e8f0;color:#94a3b8;font-size:.7rem;font-weight:700;">Coming Soon</span>
            </div>
        </div>
    </div>
</div>

@endsection
