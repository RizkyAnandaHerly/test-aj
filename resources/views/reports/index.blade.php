@extends('layouts.sidebar')

@section('title', 'Laporan Pergerakan Barang')

@section('styles')
<style>
    /* ── Summary Cards ─────────────────────────────────────── */
    .stat-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 1.9rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.02em;
    }
    .stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-top: 4px;
    }

    /* ── Export Buttons ─────────────────────────────────────── */
    .export-btn-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .export-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    .export-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .export-btn-excel {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: #fff;
    }
    .export-btn-excel:hover { color: #fff; background: linear-gradient(135deg, #15803d 0%, #166534 100%); }
    .export-btn-csv {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #fff;
    }
    .export-btn-csv:hover { color: #fff; background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%); }
    .export-btn-pdf {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #fff;
    }
    .export-btn-pdf:hover { color: #fff; background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%); }

    /* ── Filter Card ───────────────────────────────────────── */
    .filter-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .filter-card .form-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }
    .filter-card .form-select,
    .filter-card .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.85rem;
        padding: 8px 12px;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-card .form-select:focus,
    .filter-card .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        background: #fff;
    }

    /* ── Table ──────────────────────────────────────────────── */
    .report-table thead th {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
        padding: 12px 16px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .report-table tbody td {
        padding: 10px 16px;
        font-size: 0.82rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .report-table tbody tr {
        transition: background 0.15s;
    }
    .report-table tbody tr:hover {
        background: #f8fafc;
    }

    /* ── Action badges ─────────────────────────────────────── */
    .action-badge {
        font-size: 0.62rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.04em;
    }
    .action-create { background: #dcfce7; color: #166534; }
    .action-update { background: #fef9c3; color: #854d0e; }
    .action-delete { background: #fee2e2; color: #991b1b; }
    .action-default { background: #f1f5f9; color: #64748b; }

    /* ── Empty State ───────────────────────────────────────── */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 16px;
    }

    /* ── Active filter badge ───────────────────────────────── */
    .active-filter-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .filter-tag {
        font-size: 0.68rem;
        padding: 3px 10px;
        border-radius: 20px;
        background: #eff6ff;
        color: #1e40af;
        font-weight: 600;
    }
</style>
@endsection

@section('content')

{{-- ── Page Header ──────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            <i class="bi bi-file-earmark-bar-graph text-primary me-2"></i>
            Laporan Pergerakan Barang
        </h4>
        <p class="text-muted mb-0 small">
            Pantau, filter, dan unduh riwayat seluruh pergerakan barang di gudang
        </p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#dbeafe;color:#1e40af;font-size:.75rem;font-weight:600;">
        <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('d F Y') }}
    </span>
</div>

{{-- ── Filter Card ──────────────────────────────────────────── --}}
<div class="card filter-card mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-funnel-fill text-primary"></i>
            <span class="fw-bold text-dark" style="font-size:0.9rem;">Filter Laporan</span>
        </div>

        <form action="{{ route('reports.movements.index') }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-2 col-6">
                    <label class="form-label">Pengguna</label>
                    <select name="user_id" class="form-select">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label">Aksi</label>
                    <select name="action" class="form-select">
                        <option value="">Semua Aksi</option>
                        <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label">Model Terkait</label>
                    <select name="model_type" class="form-select">
                        <option value="">Semua Model</option>
                        @foreach($modelTypes as $mt)
                            <option value="{{ $mt }}" {{ request('model_type') === $mt ? 'selected' : '' }}>{{ $mt }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-2 col-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold flex-fill" style="border-radius:10px;padding:8px;">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.movements.index') }}" class="btn btn-outline-secondary btn-sm fw-bold" style="border-radius:10px;padding:8px 14px;" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>

        {{-- Active filter tags --}}
        @if(request()->hasAny(['user_id', 'action', 'model_type', 'date_from', 'date_to']))
            <div class="active-filter-info mt-3 pt-3" style="border-top:1px solid #f1f5f9;">
                <span class="text-muted small fw-semibold"><i class="bi bi-filter-circle-fill me-1"></i>Filter aktif:</span>
                @if(request('user_id'))
                    @php $filterUser = $users->firstWhere('id', request('user_id')); @endphp
                    <span class="filter-tag">Pengguna: {{ $filterUser->name ?? '-' }}</span>
                @endif
                @if(request('action'))
                    <span class="filter-tag">Aksi: {{ strtoupper(request('action')) }}</span>
                @endif
                @if(request('model_type'))
                    <span class="filter-tag">Model: {{ request('model_type') }}</span>
                @endif
                @if(request('date_from'))
                    <span class="filter-tag">Dari: {{ request('date_from') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="filter-tag">Sampai: {{ request('date_to') }}</span>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- ── Summary Cards ────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="stat-icon" style="background:#eff6ff;">📊</div>
                    <span class="stat-label" style="margin-top:0;">Total Entri</span>
                </div>
                <div class="stat-value" style="color:#1e40af;">{{ number_format($totalEntries) }}</div>
                <div class="stat-label" style="margin-top:2px;">aktivitas tercatat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="stat-icon" style="background:#dcfce7;">✅</div>
                    <span class="stat-label" style="margin-top:0;">Create</span>
                </div>
                <div class="stat-value" style="color:#16a34a;">{{ number_format($createCount) }}</div>
                <div class="stat-label" style="margin-top:2px;">data baru ditambahkan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="stat-icon" style="background:#fef9c3;">✏️</div>
                    <span class="stat-label" style="margin-top:0;">Update</span>
                </div>
                <div class="stat-value" style="color:#ca8a04;">{{ number_format($updateCount) }}</div>
                <div class="stat-label" style="margin-top:2px;">data diperbarui</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="stat-icon" style="background:#fee2e2;">🗑️</div>
                    <span class="stat-label" style="margin-top:0;">Delete</span>
                </div>
                <div class="stat-value" style="color:#dc2626;">{{ number_format($deleteCount) }}</div>
                <div class="stat-label" style="margin-top:2px;">data dihapus</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Export Buttons + Data Table ───────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-table text-primary me-2"></i>Preview Data
                </h5>
                <p class="text-muted small mb-0 mt-1">
                    Menampilkan {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} dari {{ number_format($logs->total()) }} entri
                </p>
            </div>

            <div class="export-btn-group">
                <a href="{{ route('reports.movements.export.excel', request()->query()) }}" class="export-btn export-btn-excel">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>
                <a href="{{ route('reports.movements.export.csv', request()->query()) }}" class="export-btn export-btn-csv">
                    <i class="bi bi-filetype-csv"></i> CSV
                </a>
                <a href="{{ route('reports.movements.export.pdf', request()->query()) }}" class="export-btn export-btn-pdf">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @if($logs->isNotEmpty())
            <div class="table-responsive">
                <table class="table report-table align-middle mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="ps-4" style="width:50px;">No</th>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th class="text-center">Aksi</th>
                            <th>Model / ID</th>
                            <th class="pe-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $i => $log)
                            <tr>
                                <td class="ps-4 text-muted fw-bold">
                                    {{ $logs->firstItem() + $i }}
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $log->created_at->format('H:i:s') }} WIB</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $log->user->name ?? '(deleted)' }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $log->role_name ?? '-' }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $actionClass = match($log->action) {
                                            'create' => 'action-create',
                                            'update' => 'action-update',
                                            'delete' => 'action-delete',
                                            default  => 'action-default',
                                        };
                                    @endphp
                                    <span class="action-badge {{ $actionClass }}">{{ strtoupper($log->action) }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size:.82rem;">{{ $log->model_type }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">ID: {{ $log->model_id }}</div>
                                </td>
                                <td class="pe-4 text-muted" style="font-size:.8rem; max-width:280px;">
                                    {{ Str::limit($log->description, 60) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Tidak Ada Data</h6>
                <p class="text-muted small mb-3">
                    Tidak ditemukan log aktivitas yang sesuai dengan filter yang diterapkan.
                </p>
                <a href="{{ route('reports.movements.index') }}" class="btn btn-outline-primary btn-sm fw-semibold" style="border-radius:10px;padding:6px 20px;">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
            </div>
        @endif
    </div>

    @if($logs->hasPages())
        <div class="card-footer bg-white border-top px-4 py-3">
            {{ $logs->links() }}
        </div>
    @endif
</div>

@endsection
