@extends('layouts.sidebar')

@section('title', 'Admin Dashboard')

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
    .bar-val { font-size: .62rem; font-weight: 700; color: #1e40af; }
    .bar-fill {
        width: 100%;
        background: linear-gradient(180deg,#3b82f6,#1d4ed8);
        border-radius: 4px 4px 0 0;
        min-height: 4px;
    }
    .bar-label { font-size: .62rem; color: #94a3b8; white-space: nowrap; }
    .quick-btn {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; text-align: center; padding: 18px 12px;
        border-radius: 10px; text-decoration: none; font-weight: 600;
        font-size: .82rem; gap: 6px; transition: transform .15s, box-shadow .15s;
    }
    .quick-btn:hover { transform: translateY(-2px); }
    .quick-btn .q-icon { font-size: 1.5rem; }
</style>
@endsection

@section('content')

{{-- ── Overview Header ──────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Admin Control Center</h4>
        <p class="text-muted mb-0 small">
            {{ now()->translatedFormat('l, d F Y') }}
            &nbsp;·&nbsp; Sistem aktif · Semua modul berjalan
        </p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#fef3c7;color:#92400e;font-size:.75rem;font-weight:700;">
        <i class="bi bi-shield-fill-check me-1"></i> Administrator
    </span>
</div>

{{-- ── 6 KPI Cards (3 per row) ──────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- 1. Total Produk Aktif --}}
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100 rounded-3 text-center p-3">
            <div style="font-size:1.4rem;margin-bottom:6px;">📦</div>
            <div class="fw-bold" style="font-size:1.5rem;color:#1e40af;line-height:1.1;">{{ number_format($totalProducts) }}</div>
            <div class="text-muted mt-1" style="font-size:.72rem;">Produk Aktif</div>
        </div>
    </div>

    {{-- 2. Inbound Hari Ini --}}
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100 rounded-3 text-center p-3">
            <div style="font-size:1.4rem;margin-bottom:6px;">📥</div>
            <div class="fw-bold" style="font-size:1.5rem;color:#0369a1;line-height:1.1;">{{ number_format($todayInbound) }}</div>
            <div class="text-muted mt-1" style="font-size:.72rem;">Inbound Hari Ini</div>
            <div style="font-size:.68rem;color:#3b82f6;margin-top:2px;">{{ number_format($todayQty) }} unit</div>
        </div>
    </div>

    {{-- 3. QC Pending --}}
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100 rounded-3 text-center p-3
                    {{ $pendingQC > 0 ? 'border border-warning' : '' }}">
            <div style="font-size:1.4rem;margin-bottom:6px;">🔍</div>
            <div class="fw-bold" style="font-size:1.5rem;color:{{ $pendingQC > 0 ? '#ca8a04' : '#16a34a' }};line-height:1.1;">
                {{ number_format($pendingQC) }}
            </div>
            <div class="text-muted mt-1" style="font-size:.72rem;">QC Pending</div>
        </div>
    </div>

    {{-- 4. Stok Di Bawah Minimum --}}
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100 rounded-3 text-center p-3
                    {{ $lowStock > 0 ? 'border border-danger' : '' }}">
            <div style="font-size:1.4rem;margin-bottom:6px;">⚠️</div>
            <div class="fw-bold" style="font-size:1.5rem;color:{{ $lowStock > 0 ? '#dc2626' : '#16a34a' }};line-height:1.1;">
                {{ number_format($lowStock) }}
            </div>
            <div class="text-muted mt-1" style="font-size:.72rem;">Stok Min</div>
        </div>
    </div>

    {{-- 5. Lokasi Penuh --}}
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100 rounded-3 text-center p-3">
            <div style="font-size:1.4rem;margin-bottom:6px;">📍</div>
            <div class="fw-bold" style="font-size:1.5rem;color:#c2410c;line-height:1.1;">{{ number_format($fullLocations) }}</div>
            <div class="text-muted mt-1" style="font-size:.72rem;">Lokasi Penuh</div>
        </div>
    </div>

    {{-- 6. Total User --}}
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100 rounded-3 text-center p-3">
            <div style="font-size:1.4rem;margin-bottom:6px;">👥</div>
            <div class="fw-bold" style="font-size:1.5rem;color:#7c3aed;line-height:1.1;">{{ number_format($totalUsers) }}</div>
            <div class="text-muted mt-1" style="font-size:.72rem;">Total User</div>
        </div>
    </div>

</div>

{{-- ── Quick Actions ─────────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Quick Actions
        </h6>
        <p class="text-muted small mt-1 mb-3">Akses cepat ke semua modul operasional</p>
    </div>
    <div class="card-body px-4 pb-4 pt-0">

        {{-- Row 1: Active modules --}}
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('products.index') }}" class="quick-btn"
                   style="background:#dbeafe;color:#1e40af;">
                    <span class="q-icon">📦</span> Kelola Produk
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('inbounds.create') }}" class="quick-btn"
                   style="background:#dcfce7;color:#166534;">
                    <span class="q-icon">📥</span> Input Inbound
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('locations.index') }}" class="quick-btn"
                   style="background:#ffedd5;color:#9a3412;">
                    <span class="q-icon">📍</span> Alokasi Lokasi
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('qc-inspections.index') }}" class="quick-btn"
                   style="background:#f3e8ff;color:#6b21a8;">
                    <span class="q-icon">🔬</span> Form QC
                </a>
            </div>
            {{-- Modul Sales Order yang sudah diaktifkan --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('sales-orders.index') }}" class="quick-btn"
                   style="background:#e0f2fe;color:#0369a1;">
                    <span class="q-icon">🛒</span> Sales Order
                </a>
            </div>
        </div>

        {{-- Row 2: Additional modules --}}
        <p class="text-muted" style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">
            Modul Tambahan
        </p>
        <div class="row g-2">
            {{-- Semua modul yang sudah ada backend-nya --}}
            <div class="col-6 col-md-2">
                <a href="{{ route('warehouses.index') }}" class="quick-btn"
                   style="background:#fef08a;color:#854d0e;">
                    <span class="q-icon">🏭</span> Master Gudang
                </a>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('vendors.index') }}" class="quick-btn"
                   style="background:#fec2d4;color:#9f1239;">
                    <span class="q-icon">🚚</span> Master Vendor
                </a>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('reject-items.index') }}" class="quick-btn"
                   style="background:#fed7aa;color:#92400e;">
                    <span class="q-icon">🚫</span> Reject & Karantina
                </a>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('packing-details.index') }}" class="quick-btn"
                   style="background:#d1d5db;color:#374151;">
                    <span class="q-icon">📦</span> Packing & Pelabelan
                </a>
            </div>
            {{-- Laporan masih Coming Soon karena tidak ada route --}}
            <div class="col-6 col-md-2">
                <div class="quick-btn"
                     style="background:#f8fafc;color:#94a3b8;border:1px dashed #cbd5e1;opacity:.7;cursor:not-allowed;">
                    <span class="q-icon">📊</span> Laporan
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
            Inbound 7 Hari Terakhir
        </h6>
    </div>
    <div class="card-body px-4 pb-4 pt-3">
        @php
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
                    <div class="bar-fill" style="height:{{ max(4, round(($qty/$maxVal)*100)) }}px;"></div>
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

{{-- ── Two Column: Recent Inbounds + Recent QC ────────────────────────── --}}
<div class="row g-3">

    {{-- Recent Inbounds --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-box-arrow-in-down text-primary me-2"></i>5 Inbound Terbaru
                </h6>
            </div>
            <div class="card-body p-0">
                @if($recentInbounds->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead style="background:#f8fafc;">
                                <tr>
                                    <th class="ps-4 py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Produk</th>
                                    <th class="py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.62rem;">Qty</th>
                                    <th class="pe-4 py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInbounds as $inbound)
                                    <tr>
                                        <td class="ps-4 py-2">
                                            <div class="fw-semibold text-dark" style="font-size:.8rem;">{{ Str::limit($inbound->product?->name ?? '—', 24) }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">{{ $inbound->product?->sku }}</div>
                                        </td>
                                        <td class="py-2 text-center fw-bold" style="color:#2563eb;">{{ number_format($inbound->qty) }}</td>
                                        <td class="pe-4 py-2 text-muted" style="font-size:.75rem;">{{ $inbound->received_date?->format('d/m/Y') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted small"><i class="bi bi-inbox d-block mb-2 fs-4 opacity-50"></i>Belum ada inbound</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent QC --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-shield-check text-success me-2"></i>5 QC Terbaru
                </h6>
            </div>
            <div class="card-body p-0">
                @if($recentQC->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead style="background:#f8fafc;">
                                <tr>
                                    <th class="ps-4 py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Produk</th>
                                    <th class="py-2 text-uppercase fw-bold text-secondary" style="font-size:.62rem;">Inspektor</th>
                                    <th class="pe-4 py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.62rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentQC as $qc)
                                    <tr>
                                        <td class="ps-4 py-2 fw-semibold text-dark" style="font-size:.8rem;">{{ Str::limit($qc->product?->name ?? '—', 24) }}</td>
                                        <td class="py-2 text-muted" style="font-size:.78rem;">{{ $qc->inspector?->name ?? '—' }}</td>
                                        <td class="pe-4 py-2 text-center">
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
                    <div class="text-center py-4 text-muted small"><i class="bi bi-shield-x d-block mb-2 fs-4 opacity-50"></i>Belum ada inspeksi QC</div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
