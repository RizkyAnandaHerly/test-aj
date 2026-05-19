<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QcInspectionController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // If already logged in, redirect to their dashboard
    if (auth()->check()) {
        $role = auth()->user()->role?->name;
        return match($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff'   => redirect()->route('staff.dashboard'),
            default   => redirect()->route('dashboard'),
        };
    }
    return view('landing');
})->name('landing');

Route::get('/track', [TrackController::class, 'index'])->name('track');

/*
|--------------------------------------------------------------------------
| Authenticated — Generic Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('requester.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated — Role-Specific Dashboards (with real data)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->get('/admin/dashboard', function () {
        $thisMonth = now()->startOfMonth();
        $data = [
            'totalProducts'  => \App\Models\Product::where('status', 'active')->count(),
            'todayInbound'   => \App\Models\Inbound::whereDate('received_date', today())->count(),
            'todayQty'       => \App\Models\Inbound::whereDate('received_date', today())->sum('qty'),
            'pendingQC'      => \App\Models\Inbound::whereDoesntHave('qcInspection')->count(),
            'lowStock'       => \App\Models\Product::whereColumn('stock_qty', '<=', 'min_stock')
                                    ->where('status', 'active')->count(),
            'fullLocations'  => \App\Models\Location::where('status', 'full')->count(),
            'totalUsers'     => \App\Models\User::count(),
            'recentInbounds' => \App\Models\Inbound::with('product')
                                    ->latest()->take(5)->get(),
            'recentQC'       => \App\Models\QcInspection::with(['product', 'inspector'])
                                    ->latest()->take(5)->get(),
            'chartData'      => \App\Models\Inbound::selectRaw('DATE(received_date) as date, SUM(qty) as total')
                                    ->where('received_date', '>=', now()->subDays(6))
                                    ->groupBy('date')->orderBy('date')->get(),
        ];
        return view('admin.dashboard', $data);
    })->name('admin.dashboard');

Route::middleware(['auth', 'role:manager'])
    ->get('/manager/dashboard', function () {
        $thisMonth = now()->startOfMonth();
        $data = [
            'inboundThisMonth'    => \App\Models\Inbound::where('received_date', '>=', $thisMonth)->count(),
            'inboundQtyThisMonth' => \App\Models\Inbound::where('received_date', '>=', $thisMonth)->sum('qty'),
            'qcPass'              => \App\Models\QcInspection::where('created_at', '>=', $thisMonth)
                                        ->where('status', 'pass')->count(),
            'qcFail'              => \App\Models\QcInspection::where('created_at', '>=', $thisMonth)
                                        ->where('status', 'fail')->count(),
            'qcPartial'           => \App\Models\QcInspection::where('created_at', '>=', $thisMonth)
                                        ->where('status', 'partial')->count(),
            'lowStockProducts'    => \App\Models\Product::whereColumn('stock_qty', '<=', 'min_stock')
                                        ->where('status', 'active')->get(),
            'locationStats'       => [
                'available' => \App\Models\Location::where('status', 'available')->count(),
                'reserved'  => \App\Models\Location::where('status', 'reserved')->count(),
                'full'      => \App\Models\Location::where('status', 'full')->count(),
            ],
            'chartData'           => \App\Models\Inbound::selectRaw('DATE(received_date) as date, SUM(qty) as total')
                                        ->where('received_date', '>=', now()->subDays(6))
                                        ->groupBy('date')->orderBy('date')->get(),
            'topProducts'         => \App\Models\Inbound::with('product')
                                        ->where('received_date', '>=', $thisMonth)
                                        ->selectRaw('product_id, SUM(qty) as total_qty')
                                        ->groupBy('product_id')
                                        ->orderByDesc('total_qty')
                                        ->take(5)->get(),
            'recentQC'            => \App\Models\QcInspection::with(['product', 'inspector'])
                                        ->latest()->take(5)->get(),
        ];
        return view('manager.dashboard', $data);
    })->name('manager.dashboard');

Route::middleware(['auth', 'role:staff'])
    ->get('/staff/dashboard', function () {
        $data = [
            'todayInbound'   => \App\Models\Inbound::whereDate('received_date', today())->count(),
            'todayQty'       => \App\Models\Inbound::whereDate('received_date', today())->sum('qty'),
            'pendingQC'      => \App\Models\Inbound::whereDoesntHave('qcInspection')->count(),
            'lowStock'       => \App\Models\Product::whereColumn('stock_qty', '<=', 'min_stock')
                                    ->where('status', 'active')->count(),
            'fullLocations'  => \App\Models\Location::where('status', 'full')->count(),
            'recentInbounds' => \App\Models\Inbound::with(['product', 'vendor'])
                                    ->latest()->take(5)->get(),
            'recentQC'       => \App\Models\QcInspection::with(['product', 'inspector'])
                                    ->latest()->take(5)->get(),
        ];
        return view('staff.dashboard', $data);
    })->name('staff.dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated — Profile (Breeze)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Cascading Dropdown API (no CSRF — JSON responses, auth required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('api/locations')->name('api.locations.')->group(function () {
    Route::get('/zones',   [LocationController::class, 'apiZones'])->name('zones');
    Route::get('/racks',   [LocationController::class, 'apiRacks'])->name('racks');
    Route::get('/pallets', [LocationController::class, 'apiPallets'])->name('pallets');
});

/*
|--------------------------------------------------------------------------
| Admin Only — Master Data Management
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Master Gudang (Modul 0A)
    Route::resource('warehouses', WarehouseController::class)->only([
        'index', 'create', 'store', 'edit', 'update',
    ]);

    // Master Vendor (Modul 0B)
    Route::resource('vendors', VendorController::class)->only([
        'index', 'create', 'store', 'edit', 'update',
    ]);
});

/*
|--------------------------------------------------------------------------
| All Authenticated Users — Shared Operational Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ── Katalog Barang — semua role dapat melihat ─────────────────────────
    Route::resource('products', ProductController::class)->only(['index', 'show']);

    // ── Form Inbound (admin + staff) ──────────────────────────────────────
    Route::resource('inbounds', InboundController::class)->only(['create', 'store']);

    // ── Penempatan Lokasi (admin + staff) ─────────────────────────────────
    Route::resource('locations', LocationController::class)->only([
        'index', 'create', 'store',
    ]);
    // Delete placement — uses ProductLocation model binding
    Route::delete('/locations/placement/{productLocation}', [LocationController::class, 'destroy'])
         ->name('locations.placement.destroy');

    // ── Form QC (admin + staff) — now includes show ───────────────────────
    Route::resource('qc-inspections', QcInspectionController::class)->only([
        'index', 'create', 'store', 'show',
    ]);

    // ── Sales Order ───────────────────────────────────────────────────────
    Route::resource('sales-order', SalesOrderController::class)->only(['create', 'store']);

    // ── Pencarian Posisi Barang (admin + manager + staff) — Fix 404 ───────
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // ── Activity Log (admin + manager) ────────────────────────────────────
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

Route::middleware(['auth'])->group(function () {

    // ── Katalog Barang — semua role dapat melihat ─────────────────────────
    Route::resource('products', ProductController::class)->only(['index', 'show']);

    // ── Form Inbound (admin + staff) ──────────────────────────────────────
    Route::resource('inbounds', InboundController::class)->only(['create', 'store']);

    // ── Penempatan Lokasi (admin + staff) ─────────────────────────────────
    Route::resource('locations', LocationController::class)->only([
        'index', 'create', 'store',
    ]);
    // Delete placement — uses ProductLocation model binding
    Route::delete('/locations/placement/{productLocation}', [LocationController::class, 'destroy'])
         ->name('locations.placement.destroy');

    // ── Form QC (admin + staff) — now includes show ───────────────────────
    Route::resource('qc-inspections', QcInspectionController::class)->only([
        'index', 'create', 'store', 'show',
    ]);

    // ── Sales Order (admin + staff) ───────────────────────────────────────
    // Menggunakan 'sales-orders' (jamak) agar sesuai dengan penamaan di Blade
    // Dan membuka seluruh akses resource (index, create, store, edit, update, show, destroy)
    Route::resource('sales-orders', SalesOrderController::class);

    // ── Pencarian Posisi Barang (admin + manager + staff) — Fix 404 ───────
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // ── Activity Log (admin + manager) ────────────────────────────────────
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

require __DIR__.'/auth.php';
