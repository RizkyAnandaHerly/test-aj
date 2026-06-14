<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pergerakan Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* ── Header ───────────────────────────────────────────── */
        .report-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #1e293b 100%);
            color: #fff;
            padding: 20px 30px;
            margin-bottom: 0;
        }

        .report-header h1 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 2px;
            letter-spacing: -0.01em;
        }

        .report-header .subtitle {
            font-size: 10px;
            color: #94a3b8;
        }

        .report-header .company {
            font-size: 11px;
            font-weight: 600;
            color: #93c5fd;
            margin-bottom: 4px;
        }

        /* ── Filter Info ──────────────────────────────────────── */
        .filter-info {
            background: #f1f5f9;
            padding: 10px 30px;
            font-size: 9px;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }

        .filter-info strong {
            color: #1e293b;
        }

        /* ── Summary Stats ────────────────────────────────────── */
        .summary-row {
            display: table;
            width: 100%;
            padding: 12px 30px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-cell {
            display: table-cell;
            text-align: center;
            padding: 6px 8px;
            width: 25%;
        }

        .summary-cell .value {
            font-size: 16px;
            font-weight: 800;
        }

        .summary-cell .label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            font-weight: 600;
        }

        .color-blue { color: #1e40af; }
        .color-green { color: #16a34a; }
        .color-yellow { color: #ca8a04; }
        .color-red { color: #dc2626; }

        /* ── Table ────────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            font-size: 9px;
        }

        .data-table thead th {
            background: #1e40af;
            color: #fff;
            font-weight: 700;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 8px 10px;
            text-align: left;
            border: none;
        }

        .data-table thead th:first-child {
            text-align: center;
            width: 35px;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tbody td {
            padding: 6px 10px;
            vertical-align: top;
        }

        .data-table tbody td:first-child {
            text-align: center;
            color: #94a3b8;
            font-weight: 600;
        }

        /* ── Action Labels ────────────────────────────────────── */
        .action-label {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .action-create {
            background: #dcfce7;
            color: #166534;
        }

        .action-update {
            background: #fef9c3;
            color: #854d0e;
        }

        .action-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-default {
            background: #f1f5f9;
            color: #64748b;
        }

        /* ── Footer ───────────────────────────────────────────── */
        .report-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 30px;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            background: #fff;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            text-align: left;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
        }

        /* ── Page break handling ──────────────────────────────── */
        .page-content {
            padding: 0 0 40px;
        }
    </style>
</head>
<body>

    {{-- ── Report Header ──────────────────────────────────────── --}}
    <div class="report-header">
        <div class="company">🏭 WarehouseTrack — WMS v2.0</div>
        <h1>Laporan Pergerakan Barang</h1>
        <div class="subtitle">
            Dicetak pada: {{ now()->format('d M Y H:i:s') }} WIB
            &nbsp;&middot;&nbsp;
            Oleh: {{ auth()->user()->name ?? '-' }}
        </div>
    </div>

    {{-- ── Filter Info ────────────────────────────────────────── --}}
    <div class="filter-info">
        <strong>Filter yang diterapkan:</strong>
        @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            Periode: <strong>{{ $filters['date_from'] ?? '∞' }}</strong> s/d <strong>{{ $filters['date_to'] ?? '∞' }}</strong>
            &nbsp;&middot;&nbsp;
        @endif
        @if(!empty($filters['action']))
            Aksi: <strong>{{ strtoupper($filters['action']) }}</strong>
            &nbsp;&middot;&nbsp;
        @endif
        @if(!empty($filters['model_type']))
            Model: <strong>{{ $filters['model_type'] }}</strong>
            &nbsp;&middot;&nbsp;
        @endif
        @if(!empty($filters['user_id']))
            User ID: <strong>{{ $filters['user_id'] }}</strong>
            &nbsp;&middot;&nbsp;
        @endif
        @if(empty(array_filter($filters)))
            <em>Tidak ada filter (semua data)</em>
        @endif
        &nbsp;&middot;&nbsp;
        Total: <strong>{{ number_format($logs->count()) }} entri</strong>
    </div>

    {{-- ── Summary Stats ──────────────────────────────────────── --}}
    <div class="summary-row">
        <div class="summary-cell">
            <div class="value color-blue">{{ number_format($logs->count()) }}</div>
            <div class="label">Total Entri</div>
        </div>
        <div class="summary-cell">
            <div class="value color-green">{{ number_format($logs->where('action', 'create')->count()) }}</div>
            <div class="label">Create</div>
        </div>
        <div class="summary-cell">
            <div class="value color-yellow">{{ number_format($logs->where('action', 'update')->count()) }}</div>
            <div class="label">Update</div>
        </div>
        <div class="summary-cell">
            <div class="value color-red">{{ number_format($logs->where('action', 'delete')->count()) }}</div>
            <div class="label">Delete</div>
        </div>
    </div>

    {{-- ── Data Table ─────────────────────────────────────────── --}}
    <div class="page-content">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Aksi</th>
                    <th>Model</th>
                    <th>ID</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $i => $log)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->user->name ?? '(deleted)' }}</td>
                        <td>{{ $log->role_name ?? '-' }}</td>
                        <td>
                            @php
                                $cls = match($log->action) {
                                    'create' => 'action-create',
                                    'update' => 'action-update',
                                    'delete' => 'action-delete',
                                    default  => 'action-default',
                                };
                            @endphp
                            <span class="action-label {{ $cls }}">{{ strtoupper($log->action) }}</span>
                        </td>
                        <td>{{ $log->model_type }}</td>
                        <td>{{ $log->model_id }}</td>
                        <td>{{ Str::limit($log->description, 50) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:30px;color:#94a3b8;">
                            Tidak ada data yang sesuai dengan filter
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Footer ─────────────────────────────────────────────── --}}
    <div class="report-footer">
        <div class="footer-left">
            WarehouseTrack — Laporan Pergerakan Barang
        </div>
        <div class="footer-right">
            Dicetak: {{ now()->format('d/m/Y H:i:s') }} WIB
        </div>
    </div>

</body>
</html>
