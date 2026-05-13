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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated — Generic Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated — Role-Specific Dashboards
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Admin Dashboard</h1><p>Logged in as: ' . auth()->user()->name . '</p>';
    })->name('dashboard');
});

Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Manager Dashboard</h1><p>Logged in as: ' . auth()->user()->name . '</p>';
    })->name('dashboard');
});

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Staff Dashboard</h1><p>Logged in as: ' . auth()->user()->name . '</p>';
    })->name('dashboard');
});

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

require __DIR__.'/auth.php';
