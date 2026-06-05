<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display paginated activity log with filters.
     * Access: admin + manager only (enforced via route middleware).
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderByDesc('created_at');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(25)->withQueryString();

        // For filter dropdowns
        $modelTypes = ActivityLog::distinct()->orderBy('model_type')->pluck('model_type');
        $users      = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('activity-logs.index', compact('logs', 'modelTypes', 'users'));
    }
}
