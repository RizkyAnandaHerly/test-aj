@extends('layouts.sidebar')

@section('title', 'Staff Dashboard')

@section('content')

{{-- ── Welcome Bar ──────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            Halo, {{ Auth::user()->name }}! 👋
        </h4>
        <p class="text-muted mb-0 small">
            {{ now()->translatedFormat('l, d F Y') }} — Hari ini adalah hari yang produktif!
        </p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#dbeafe; color:#1e40af; font-size:0.75rem; font-weight:600;">
        <i class="bi bi-person-badge me-1"></i> Staff Gudang
    </span>
</div>

{{-- ── Flash Messages ───────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
        <span class="fw-semibold">{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── KPI Cards ────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Card 1: Inbound Hari Ini --}}
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#dbeafe;font-size:1.3rem;">
                        📦
                    </div>
                    <span class="badge rounded-pill" style="background:#dbeafe;color:#1e40af;font-size:0.65rem;">Hari Ini</span>
                </div>
                <div class="fw-bold" style="font-size:1.6rem;color:#1e40af;line-height:1;">
                    {{ $todayInbound }}
                </div>
                <div class="text-muted small mt-1">Inbound Hari Ini</div>
                <div class="fw-semibold" style="font-size:0.75rem;color:#3b82f6;margin-top:2px;">
                    {{ number_format($todayQty) }} unit masuk
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Menunggu QC --}}
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3
                    {{ $pendingQC > 0 ? 'border-warning border' : '' }}">
            <div class="card-body p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#fef9c3;font-size:1.3rem;">
                        🔍
                    </div>
                    @if($pendingQC > 0)
                        <span class="badge rounded-pill" style="background:#fef9c3;color:#854d0e;font-size:0.65rem;">
                            Perlu Perhatian
                        </span>
                    @endif
                </div>
                <div class="fw-bold" style="font-size:1.6rem;color:#854d0e;line-height:1;">
                    {{ $pendingQC }}
                </div>
                <div class="text-muted small mt-1">Menunggu QC</div>
                <div style="font-size:0.75rem;color:#ca8a04;margin-top:2px;">
                    inbound belum diperiksa
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Stok Minimum --}}
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3
                    {{ $lowStock > 0 ? 'border-danger border' : '' }}">
            <div class="card-body p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#fee2e2;font-size:1.3rem;">
                        ⚠️
                    </div>
                    @if($lowStock > 0)
                        <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-size:0.65rem;">
                            Segera Restock
                        </span>
                    @endif
                </div>
                <div class="fw-bold" style="font-size:1.6rem;color:#991b1b;line-height:1;">
                    {{ $lowStock }}
                </div>
                <div class="text-muted small mt-1">Stok Minimum</div>
                <div style="font-size:0.75rem;color:#ef4444;margin-top:2px;">
                    produk di bawah minimum
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Lokasi Penuh --}}
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:#ffedd5;font-size:1.3rem;">
                        📍
                    </div>
                    <span class="badge rounded-pill" style="background:#ffedd5;color:#9a3412;font-size:0.65rem;">Kapasitas</span>
                </div>
                <div class="fw-bold" style="font-size:1.6rem;color:#c2410c;line-height:1;">
                    {{ $fullLocations }}
                </div>
                <div class="text-muted small mt-1">Lokasi Penuh</div>
                <div style="font-size:0.75rem;color:#ea580c;margin-top:2px;">
                    rak / palet sudah penuh
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Quick Action Buttons ─────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat
        </h6>
        <p class="text-muted small mt-1 mb-3">Pilih tugas operasional yang ingin dikerjakan sekarang</p>
    </div>
    <div class="card-body px-4 pb-4 pt-0">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <a href="{{ route('inbounds.create') }}"
                   class="d-flex flex-column align-items-center justify-content-center text-center text-decoration-none p-4 rounded-3 h-100"
                   style="background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;transition:transform .15s,box-shadow .15s;"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(37,99,235,.35)'"
                   onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div style="font-size:2rem;margin-bottom:10px;">📥</div>
                    <div class="fw-bold mb-1" style="font-size:0.95rem;">+ Input Inbound Baru</div>
                    <div style="font-size:0.75rem;opacity:.85;">Daftarkan barang masuk dari vendor</div>
                </a>
            </div>

            <div class="col-12 col-md-4">
                <a href="{{ route('locations.create') }}"
                   class="d-flex flex-column align-items-center justify-content-center text-center text-decoration-none p-4 rounded-3 h-100"
                   style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;transition:transform .15s,box-shadow .15s;"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(22,163,74,.35)'"
                   onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div style="font-size:2rem;margin-bottom:10px;">📍</div>
                    <div class="fw-bold mb-1" style="font-size:0.95rem;">+ Alokasi Lokasi Barang</div>
                    <div style="font-size:0.75rem;opacity:.85;">Tempatkan barang ke rak / palet</div>
                </a>
            </div>

            <div class="col-12 col-md-4">
                <a href="{{ route('qc-inspections.create') }}"
                   class="d-flex flex-column align-items-center justify-content-center text-center text-decoration-none p-4 rounded-3 h-100"
                   style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;transition:transform .15s,box-shadow .15s;"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(124,58,237,.35)'"
                   onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div style="font-size:2rem;margin-bottom:10px;">🔬</div>
                    <div class="fw-bold mb-1" style="font-size:0.95rem;">+ Input Hasil QC</div>
                    <div style="font-size:0.75rem;opacity:.85;">Isi form pemeriksaan kualitas barang</div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- ── Recent Tables ────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Recent Inbounds --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-box-arrow-in-down text-primary me-2"></i>Inbound Terbaru
                </h6>
                <span class="badge rounded-pill" style="background:#f1f5f9;color:#64748b;font-size:0.7rem;">5 terakhir</span>
            </div>
            <div class="card-body p-0">
                @if($recentInbounds->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead style="background:#f8fafc;">
                                <tr>
                                    <th class="ps-4 py-2 text-uppercase fw-bold text-secondary" style="font-size:.65rem;">Produk</th>
                                    <th class="py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.65rem;">Qty</th>
                                    <th class="py-2 text-uppercase fw-bold text-secondary" style="font-size:.65rem;">Vendor</th>
                                    <th class="pe-4 py-2 text-uppercase fw-bold text-secondary" style="font-size:.65rem;">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInbounds as $inbound)
                                    <tr>
                                        <td class="ps-4 py-2">
                                            <div class="fw-semibold text-dark" style="font-size:.8rem;">
                                                {{ Str::limit($inbound->product?->name ?? '—', 22) }}
                                            </div>
                                            <div class="text-muted" style="font-size:.7rem;">
                                                {{ $inbound->product?->sku }}
                                            </div>
                                        </td>
                                        <td class="py-2 text-center">
                                            <span class="fw-bold" style="color:#2563eb;">{{ number_format($inbound->qty) }}</span>
                                        </td>
                                        <td class="py-2 text-muted" style="font-size:.78rem;">
                                            {{ $inbound->vendor?->name ?? '—' }}
                                        </td>
                                        <td class="pe-4 py-2 text-muted" style="font-size:.75rem;">
                                            {{ $inbound->received_date?->format('d/m/Y') ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                        <span class="small">Belum ada data inbound hari ini</span>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white border-top px-4 py-2">
                <a href="{{ route('inbounds.create') }}" class="small text-primary fw-semibold text-decoration-none">
                    + Input Inbound Baru <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Recent QC --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-bottom pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-shield-check text-success me-2"></i>QC Terbaru
                </h6>
                <span class="badge rounded-pill" style="background:#f1f5f9;color:#64748b;font-size:0.7rem;">5 terakhir</span>
            </div>
            <div class="card-body p-0">
                @if($recentQC->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead style="background:#f8fafc;">
                                <tr>
                                    <th class="ps-4 py-2 text-uppercase fw-bold text-secondary" style="font-size:.65rem;">Produk</th>
                                    <th class="py-2 text-uppercase fw-bold text-secondary" style="font-size:.65rem;">Inspektor</th>
                                    <th class="py-2 text-uppercase fw-bold text-secondary text-center" style="font-size:.65rem;">Status</th>
                                    <th class="pe-4 py-2 text-uppercase fw-bold text-secondary" style="font-size:.65rem;">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentQC as $qc)
                                    <tr>
                                        <td class="ps-4 py-2">
                                            <div class="fw-semibold text-dark" style="font-size:.8rem;">
                                                {{ Str::limit($qc->product?->name ?? '—', 22) }}
                                            </div>
                                        </td>
                                        <td class="py-2 text-muted" style="font-size:.78rem;">
                                            {{ $qc->inspector?->name ?? '—' }}
                                        </td>
                                        <td class="py-2 text-center">
                                            @if($qc->status === 'pass')
                                                <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;font-size:.65rem;">Lulus</span>
                                            @elseif($qc->status === 'fail')
                                                <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-size:.65rem;">Gagal</span>
                                            @else
                                                <span class="badge rounded-pill" style="background:#fef9c3;color:#854d0e;font-size:.65rem;">Parsial</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 py-2 text-muted" style="font-size:.75rem;">
                                            {{ $qc->inspection_date?->format('d/m/Y') ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-shield-x fs-3 d-block mb-2 opacity-50"></i>
                        <span class="small">Belum ada data inspeksi QC</span>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white border-top px-4 py-2">
                <a href="{{ route('qc-inspections.index') }}" class="small text-success fw-semibold text-decoration-none">
                    Lihat Semua QC <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

{{-- ── Coming Soon Modules ───────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-rocket-takeoff text-secondary me-2"></i>Fitur Berikutnya
        </h6>
        <p class="text-muted small mt-1 mb-3">Modul-modul ini sedang dalam pengembangan</p>
    </div>
    <div class="card-body px-4 pb-4 pt-0">
        <div class="row g-2">
            @foreach([
                ['icon' => '🚫', 'name' => 'Reject & Karantina',  'desc' => 'Penanganan barang gagal QC'],
                ['icon' => '📦', 'name' => 'Packing & Pelabelan', 'desc' => 'Proses pengemasan barang'],
                ['icon' => '🏅', 'name' => 'Sertifikasi',         'desc' => 'Manajemen sertifikat produk'],
                ['icon' => '🛒', 'name' => 'Sales Order',         'desc' => 'Pemrosesan pesanan keluar'],
                ['icon' => '📄', 'name' => 'Dokumen Pengiriman',  'desc' => 'Surat jalan & dokumen kirim'],
                ['icon' => '📊', 'name' => 'Stock Opname',        'desc' => 'Penghitungan fisik stok'],
            ] as $module)
                <div class="col-6 col-md-4 col-lg-2">
                    @if($module['name'] === 'Reject & Karantina')
                        <a href="{{ route('reject-items.index') }}"
                           class="d-flex flex-column align-items-center justify-content-center text-center p-3 rounded-3 h-100 text-decoration-none text-dark"
                           style="background:#f8fafc;border:1px dashed #cbd5e1;">
                            <div style="font-size:1.5rem;margin-bottom:6px;">{{ $module['icon'] }}</div>
                            <div class="fw-semibold text-dark" style="font-size:.75rem;margin-bottom:4px;">{{ $module['name'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;margin-bottom:6px;">{{ $module['desc'] }}</div>
                            <span class="badge rounded-pill" style="background:#d1fae5;color:#166534;font-size:.6rem;font-weight:700;">Buka</span>
                        </a>
                    @elseif($module['name'] === 'Packing & Pelabelan')
                        <a href="{{ route('packing-details.index') }}"
                           class="d-flex flex-column align-items-center justify-content-center text-center p-3 rounded-3 h-100 text-decoration-none text-dark"
                           style="background:#f8fafc;border:1px dashed #cbd5e1;">
                            <div style="font-size:1.5rem;margin-bottom:6px;">{{ $module['icon'] }}</div>
                            <div class="fw-semibold text-dark" style="font-size:.75rem;margin-bottom:4px;">{{ $module['name'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;margin-bottom:6px;">{{ $module['desc'] }}</div>
                            <span class="badge rounded-pill" style="background:#d1fae5;color:#166534;font-size:.6rem;font-weight:700;">Buka</span>
                        </a>
                    @elseif($module['name'] === 'Sertifikasi')
                        <a href="{{ route('certifications.index') }}"
                           class="d-flex flex-column align-items-center justify-content-center text-center p-3 rounded-3 h-100 text-decoration-none text-dark"
                           style="background:#f8fafc;border:1px dashed #cbd5e1;">
                            <div style="font-size:1.5rem;margin-bottom:6px;">{{ $module['icon'] }}</div>
                            <div class="fw-semibold text-dark" style="font-size:.75rem;margin-bottom:4px;">{{ $module['name'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;margin-bottom:6px;">{{ $module['desc'] }}</div>
                            <span class="badge rounded-pill" style="background:#d1fae5;color:#166534;font-size:.6rem;font-weight:700;">Buka</span>
                        </a>
                    @elseif($module['name'] === 'Sales Order')
                        <a href="{{ route('sales-orders.index') }}"
                           class="d-flex flex-column align-items-center justify-content-center text-center p-3 rounded-3 h-100 text-decoration-none text-dark"
                           style="background:#f8fafc;border:1px dashed #cbd5e1;">
                            <div style="font-size:1.5rem;margin-bottom:6px;">{{ $module['icon'] }}</div>
                            <div class="fw-semibold text-dark" style="font-size:.75rem;margin-bottom:4px;">{{ $module['name'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;margin-bottom:6px;">{{ $module['desc'] }}</div>
                            <span class="badge rounded-pill" style="background:#d1fae5;color:#166534;font-size:.6rem;font-weight:700;">Buka</span>
                        </a>
                    @elseif($module['name'] === 'Dokumen Pengiriman')
                        <a href="{{ route('shipping-documents.index') }}"
                           class="d-flex flex-column align-items-center justify-content-center text-center p-3 rounded-3 h-100 text-decoration-none text-dark"
                           style="background:#f8fafc;border:1px dashed #cbd5e1;">
                            <div style="font-size:1.5rem;margin-bottom:6px;">{{ $module['icon'] }}</div>
                            <div class="fw-semibold text-dark" style="font-size:.75rem;margin-bottom:4px;">{{ $module['name'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;margin-bottom:6px;">{{ $module['desc'] }}</div>
                            <span class="badge rounded-pill" style="background:#d1fae5;color:#166534;font-size:.6rem;font-weight:700;">Buka</span>
                        </a>
                    @elseif($module['name'] === 'Stock Opname')
                        <a href="{{ route('stock-opnames.index') }}"
                           class="d-flex flex-column align-items-center justify-content-center text-center p-3 rounded-3 h-100 text-decoration-none text-dark"
                           style="background:#f8fafc;border:1px dashed #cbd5e1;">
                            <div style="font-size:1.5rem;margin-bottom:6px;">{{ $module['icon'] }}</div>
                            <div class="fw-semibold text-dark" style="font-size:.75rem;margin-bottom:4px;">{{ $module['name'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;margin-bottom:6px;">{{ $module['desc'] }}</div>
                            <span class="badge rounded-pill" style="background:#d1fae5;color:#166534;font-size:.6rem;font-weight:700;">Buka</span>
                        </a>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center text-center p-3 rounded-3 h-100"
                             style="background:#f8fafc;border:1px dashed #cbd5e1;opacity:.65;">
                            <div style="font-size:1.5rem;margin-bottom:6px;">{{ $module['icon'] }}</div>
                            <div class="fw-semibold text-dark" style="font-size:.75rem;margin-bottom:4px;">{{ $module['name'] }}</div>
                            <div class="text-muted" style="font-size:.65rem;margin-bottom:6px;">{{ $module['desc'] }}</div>
                            <span class="badge rounded-pill" style="background:#e2e8f0;color:#94a3b8;font-size:.6rem;font-weight:700;">Coming Soon</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
