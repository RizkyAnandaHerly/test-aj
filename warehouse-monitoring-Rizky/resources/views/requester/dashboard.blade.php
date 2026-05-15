@extends('layouts.sidebar')

@section('title', 'Dashboard Requester')

@section('content')

{{-- ── Welcome ───────────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            Halo, {{ Auth::user()->name }}! 👋
        </h4>
        <p class="text-muted mb-0 small">
            Pantau status pesanan Anda di sini · {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#f3e8ff;color:#6b21a8;font-size:.75rem;font-weight:600;">
        <i class="bi bi-person me-1"></i> Requester
    </span>
</div>

{{-- ── Info Card ─────────────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4 p-md-5 text-center">

        {{-- Illustration --}}
        <div style="font-size:4rem;margin-bottom:16px;line-height:1;">🚧</div>

        <h5 class="fw-bold text-dark mb-2">Fitur Tracking Order Sedang Dikembangkan</h5>
        <p class="text-muted mb-0" style="max-width:480px;margin:0 auto;font-size:.9rem;">
            Pesanan Anda akan dapat dipantau secara real-time di halaman ini.
            Tim pengembang sedang membangun sistem pelacakan yang canggih untuk Anda.
        </p>

        <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">
            <span class="badge px-3 py-2 rounded-pill"
                  style="background:#f1f5f9;color:#64748b;font-size:.75rem;">
                <i class="bi bi-clock me-1"></i> Estimasi: Sprint 3
            </span>
            <span class="badge px-3 py-2 rounded-pill"
                  style="background:#dcfce7;color:#166534;font-size:.75rem;">
                <i class="bi bi-check2 me-1"></i> Dalam Pengembangan
            </span>
        </div>
    </div>
</div>

{{-- ── Coming Soon: What to Expect ──────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0">
            <i class="bi bi-rocket-takeoff text-secondary me-2"></i>Yang Akan Hadir
        </h6>
        <p class="text-muted small mt-1 mb-3">Fitur-fitur yang sedang disiapkan untuk Anda</p>
    </div>
    <div class="card-body px-4 pb-4 pt-0">
        <div class="row g-3">

            {{-- Status Order --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <div style="font-size:1.5rem;margin-bottom:8px;">📋</div>
                    <div class="fw-semibold text-dark mb-1" style="font-size:.85rem;">Status Pesanan</div>
                    <div class="text-muted mb-3" style="font-size:.75rem;">
                        Lacak perjalanan pesanan Anda dari UNPAID hingga COMPLETED
                    </div>
                    {{-- Status flow --}}
                    <div class="d-flex flex-column gap-1">
                        @foreach([
                            ['UNPAID','#94a3b8'],
                            ['IN_PROGRESS','#3b82f6'],
                            ['DELIVERED','#16a34a'],
                            ['COMPLETED','#7c3aed'],
                        ] as [$st, $col])
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:8px;height:8px;border-radius:50%;background:{{ $col }};flex-shrink:0;"></div>
                                <span style="font-size:.68rem;color:{{ $col }};font-weight:600;">{{ $st }}</span>
                            </div>
                        @endforeach
                    </div>
                    <span class="badge rounded-pill mt-2 d-inline-block" style="background:#e2e8f0;color:#94a3b8;font-size:.62rem;">Coming Soon</span>
                </div>
            </div>

            {{-- Estimasi --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <div style="font-size:1.5rem;margin-bottom:8px;">⏱️</div>
                    <div class="fw-semibold text-dark mb-1" style="font-size:.85rem;">Estimasi Penyelesaian</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        Dapatkan perkiraan waktu kapan pesanan Anda siap dikirimkan
                    </div>
                    <span class="badge rounded-pill mt-2 d-inline-block" style="background:#e2e8f0;color:#94a3b8;font-size:.62rem;">Coming Soon</span>
                </div>
            </div>

            {{-- Posisi Barang --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <div style="font-size:1.5rem;margin-bottom:8px;">📍</div>
                    <div class="fw-semibold text-dark mb-1" style="font-size:.85rem;">Posisi Barang di Gudang</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        Lihat di zona / rak mana barang Anda sedang disimpan
                    </div>
                    <span class="badge rounded-pill mt-2 d-inline-block" style="background:#e2e8f0;color:#94a3b8;font-size:.62rem;">Coming Soon</span>
                </div>
            </div>

            {{-- Notifikasi --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <div style="font-size:1.5rem;margin-bottom:8px;">🔔</div>
                    <div class="fw-semibold text-dark mb-1" style="font-size:.85rem;">Notifikasi Otomatis</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        Terima pemberitahuan saat status pesanan berubah
                    </div>
                    <span class="badge rounded-pill mt-2 d-inline-block" style="background:#e2e8f0;color:#94a3b8;font-size:.62rem;">Coming Soon</span>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── Contact Info ─────────────────────────────────────────────────────── --}}
<div class="card border-0 rounded-3" style="background:linear-gradient(135deg,#1e293b,#334155);">
    <div class="card-body p-4 d-flex align-items-center gap-4 flex-wrap">
        <div style="font-size:2rem;flex-shrink:0;">📞</div>
        <div class="flex-1">
            <div class="fw-bold text-white mb-1">Butuh bantuan?</div>
            <div class="text-muted" style="font-size:.82rem;color:#94a3b8!important;">
                Hubungi tim gudang kami untuk menanyakan status pesanan Anda secara langsung.
            </div>
        </div>
    </div>
</div>

@endsection
