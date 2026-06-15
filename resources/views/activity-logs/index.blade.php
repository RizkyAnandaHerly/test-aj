@extends('layouts.sidebar')
@section('title', 'Activity Log')
@section('content')

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('activity-logs.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small mb-1">Pengguna</label>
                    <select name="user_id" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small mb-1">Model</label>
                    <select name="model_type" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Model</option>
                        @foreach($modelTypes as $mt)
                            <option value="{{ $mt }}" {{ request('model_type') === $mt ? 'selected' : '' }}>{{ $mt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small mb-1">Aksi</label>
                    <select name="action" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Aksi</option>
                        <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control form-control-sm bg-light border-0"
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control form-control-sm bg-light border-0"
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Log Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">Log Perubahan Data</h5>
                <p class="text-muted small mb-0">Seluruh operasi create / update / delete tercatat di sini</p>
            </div>
            
            {{-- Bagian ini yang ditambahkan: Membungkus tombol Export dan Badge dalam satu flexbox --}}
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('reports.movements.export.excel') }}" class="btn btn-success btn-sm fw-semibold shadow-sm d-flex align-items-center gap-1">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
                <span class="badge bg-secondary rounded-pill">{{ $logs->total() }} entri</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">Waktu</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Pengguna</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Aksi</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Model / ID</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4">
                                    <div class="text-dark small fw-medium">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-muted small">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $log->user->name ?? '(deleted)' }}</div>
                                    <div class="text-muted small">{{ $log->role_name }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $cls = match($log->action) {
                                            'create' => 'bg-success-subtle text-success border-success-subtle',
                                            'update' => 'bg-warning-subtle text-warning border-warning-subtle',
                                            'delete' => 'bg-danger-subtle text-danger border-danger-subtle',
                                            default  => 'bg-secondary-subtle text-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $cls }} border rounded-pill px-3">{{ strtoupper($log->action) }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $log->model_type }}</div>
                                    <div class="text-muted small">ID: {{ $log->model_id }}</div>
                                </td>
                                <td class="pe-4 text-muted small">{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                                    Belum ada log aktivitas yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div class="card-footer bg-white border-top px-4 py-3">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection