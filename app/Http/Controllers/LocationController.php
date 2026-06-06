<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use App\Models\ProductLocation;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════════
    // PAGES
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * List all current product placements.
     */
    public function index()
    {
        $placements = ProductLocation::with(['product', 'location', 'location.warehouse'])
                                     ->get();

        return view('locations.index', compact('placements'));
    }

    /**
     * Form to assign a product to a storage location.
     * Passes warehouses for the cascading dropdown.
     */
    public function create()
    {
        $products   = Product::where('status', 'active')
                             ->orderBy('name')
                             ->get(['id', 'name', 'sku']);

        $warehouses = Warehouse::where('status', 'active')
                               ->orderBy('name')
                               ->get(['id', 'name', 'code']);

        return view('locations.create', compact('products', 'warehouses'));
    }

    /**
     * Persist a new product placement.
     *
     * CRITICAL: uses manual find-then-save (NOT DB::raw) to avoid integer-cast crash.
     * All DB writes are inside a single DB::transaction().
     */
    public function store(Request $request)
    {
        // ── 1. Validate ───────────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'product_id'  => ['required', 'integer', 'exists:products,id'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'qty_stored'  => ['required', 'integer', 'min:1', 'max:200'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // ── 2. Capacity check (outside transaction — read-only) ───────────────
        $location   = Location::findOrFail($validated['location_id']);
        $currentQty = ProductLocation::where('location_id', $location->id)
                                     ->sum('qty_stored');

        if (($currentQty + $validated['qty_stored']) > $location->capacity) {
            $available = $location->capacity - $currentQty;

            return back()
                ->withInput()
                ->withErrors([
                    'qty_stored' => 'Kapasitas tidak mencukupi. Tersedia: '
                        . $available . ' dari kapasitas ' . $location->capacity . '.',
                ]);
        }

        // ── 3. Upsert + status update (atomic) ───────────────────────────────
        // ✅ CORRECT pattern: manual find-then-save (NO DB::raw on integer column)
        DB::transaction(function () use ($validated, $location) {
            $row = ProductLocation::where('product_id',  $validated['product_id'])
                                  ->where('location_id', $validated['location_id'])
                                  ->first();

            if ($row) {
                $row->qty_stored += (int) $validated['qty_stored'];
                $row->save();
            } else {
                ProductLocation::create([
                    'product_id'  => $validated['product_id'],
                    'location_id' => $validated['location_id'],
                    'qty_stored'  => (int) $validated['qty_stored'],
                ]);
            }

            // Recalculate total AFTER write
            $totalQty = ProductLocation::where('location_id', $location->id)
                                       ->sum('qty_stored');

            $location->update([
                'status' => match (true) {
                    $totalQty >= $location->capacity => 'full',
                    $totalQty > 0                   => 'reserved',
                    default                         => 'available',
                },
            ]);
        });

        return redirect()->route('locations.index')
                         ->with('success', 'Penempatan barang berhasil disimpan.');
    }

    /**
     * Delete a product placement record and recalculate location status.
     * Fix for: "Tombol hapus di index belum ada fungsi"
     */
    public function destroy(ProductLocation $productLocation)
    {
        $location = $productLocation->location;

        DB::transaction(function () use ($productLocation, $location) {
            $productLocation->delete();

            $totalQty = ProductLocation::where('location_id', $location->id)
                                       ->sum('qty_stored');

            $location->update([
                'status' => match (true) {
                    $totalQty >= $location->capacity => 'full',
                    $totalQty > 0                   => 'reserved',
                    default                         => 'available',
                },
            ]);
        });

        return redirect()->route('locations.index')
                         ->with('success', 'Penempatan barang berhasil dihapus.');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // CASCADING DROPDOWN API ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * GET /api/locations/zones?warehouse_id={id}
     * Returns distinct zones inside a warehouse (non-full locations only).
     */
    public function apiZones(Request $request)
    {
        $request->validate(['warehouse_id' => 'required|integer|exists:warehouses,id']);

        $zones = Location::where('warehouse_id', $request->warehouse_id)
                         ->where('status', '!=', 'full')
                         ->distinct()
                         ->orderBy('zone')
                         ->pluck('zone');

        return response()->json($zones);
    }

    /**
     * GET /api/locations/racks?warehouse_id={id}&zone={zone}
     * Returns racks (with remaining capacity) for a warehouse + zone.
     */
    public function apiRacks(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'zone'         => 'required|string',
        ]);

        $racks = Location::where('warehouse_id', $request->warehouse_id)
                         ->where('zone', $request->zone)
                         ->where('status', '!=', 'full')
                         ->whereNull('pallet_code')       // rack-level rows only
                         ->withSum('productLocations', 'qty_stored')
                         ->get()
                         ->map(fn ($loc) => [
                             'id'        => $loc->id,
                             'rack_code' => $loc->rack_code,
                             'remaining' => $loc->capacity - ($loc->product_locations_sum_qty_stored ?? 0),
                             'capacity'  => $loc->capacity,
                         ]);

        return response()->json($racks);
    }

    /**
     * GET /api/locations/pallets?warehouse_id={id}&zone={zone}&rack={rack_code}
     * Returns pallets under a rack (may be empty if rack has no sub-pallets).
     */
    public function apiPallets(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'zone'         => 'required|string',
            'rack'         => 'required|string',
        ]);

        $pallets = Location::where('warehouse_id', $request->warehouse_id)
                           ->where('zone', $request->zone)
                           ->where('rack_code', $request->rack)
                           ->whereNotNull('pallet_code')
                           ->where('status', '!=', 'full')
                           ->withSum('productLocations', 'qty_stored')
                           ->get()
                           ->map(fn ($loc) => [
                               'id'          => $loc->id,
                               'pallet_code' => $loc->pallet_code,
                               'remaining'   => $loc->capacity - ($loc->product_locations_sum_qty_stored ?? 0),
                               'capacity'    => $loc->capacity,
                           ]);

        return response()->json($pallets);
    }
}