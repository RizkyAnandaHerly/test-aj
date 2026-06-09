<?php

namespace App\Http\Controllers;

use App\Exports\ItemMovementExport;
use App\Models\ActivityLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Build a base query with applied filters.
     */
    private function buildQuery(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return $query;
    }

    /**
     * Extract filter array from the request.
     */
    private function getFilters(Request $request): array
    {
        return $request->only(['date_from', 'date_to', 'action', 'model_type', 'user_id']);
    }

    /**
     * Show the report index page with filter UI and data preview.
     */
    public function index(Request $request)
    {
        $query = $this->buildQuery($request);

        // Stats for summary cards
        $statsQuery = $this->buildQuery($request);
        $totalEntries = (clone $statsQuery)->count();
        $createCount  = (clone $statsQuery)->where('action', 'create')->count();
        $updateCount  = (clone $statsQuery)->where('action', 'update')->count();
        $deleteCount  = (clone $statsQuery)->where('action', 'delete')->count();

        // Paginated data for preview
        $logs = $query->paginate(15)->appends($request->query());

        // Filter options
        $users      = User::orderBy('name')->get(['id', 'name']);
        $modelTypes = ActivityLog::select('model_type')->distinct()->orderBy('model_type')->pluck('model_type');

        return view('reports.index', compact(
            'logs',
            'users',
            'modelTypes',
            'totalEntries',
            'createCount',
            'updateCount',
            'deleteCount'
        ));
    }

    /**
     * Export to Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $filters  = $this->getFilters($request);
        $filename = 'Laporan_Pergerakan_Barang_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ItemMovementExport($filters), $filename);
    }

    /**
     * Export to CSV.
     */
    public function exportCsv(Request $request)
    {
        $filters  = $this->getFilters($request);
        $filename = 'Laporan_Pergerakan_Barang_' . now()->format('Ymd_His') . '.csv';

        return Excel::download(new ItemMovementExport($filters), $filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Export to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = $this->buildQuery($request);
        $logs  = $query->get();

        $filters = $this->getFilters($request);

        $pdf = Pdf::loadView('reports.pdf', [
            'logs'    => $logs,
            'filters' => $filters,
        ])->setPaper('a4', 'landscape');

        $filename = 'Laporan_Pergerakan_Barang_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}