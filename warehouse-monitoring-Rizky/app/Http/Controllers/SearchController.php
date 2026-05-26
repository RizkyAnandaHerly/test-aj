<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use App\Models\ProductLocation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search for product locations across all warehouses.
     *
     * Query parameters: ?q=keyword
     * Accessible by admin, manager, staff (enforced via route middleware).
     *
     * Fix for Revisi Dosen No.2 — route was missing, causing 404.
     */
    public function index(Request $request)
    {
        $q       = $request->input('q', '');
        $results = collect();

        if ($q !== '') {
            $like = "%{$q}%";

            $results = ProductLocation::with([
                'product',
                'location',
                'location.warehouse',
            ])
            ->join('products',   'product_locations.product_id',  '=', 'products.id')
            ->join('locations',  'product_locations.location_id', '=', 'locations.id')
            ->leftJoin('warehouses', 'locations.warehouse_id',    '=', 'warehouses.id')
            ->where(function ($q2) use ($like) {
                $q2->where('products.name',      'like', $like)
                   ->orWhere('products.sku',      'like', $like)
                   ->orWhere('locations.zone',    'like', $like)
                   ->orWhere('locations.rack_code', 'like', $like)
                   ->orWhere('warehouses.name',   'like', $like);
            })
            ->orderBy('warehouses.name')
            ->orderBy('locations.zone')
            ->orderBy('locations.rack_code')
            ->select('product_locations.*')
            ->get();
        }

        return view('search.index', compact('results', 'q'));
    }

    /**
     * Advanced location search dengan filter multi-parameter.
     * Untuk proses picking — cari lokasi barang berdasarkan kode rak/palet.
     */
    public function locationAdvanced(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $warehouse_id = $request->input('warehouse_id', '');
        $zone = $request->input('zone', '');
        $results = collect();
        $warehouses = \App\Models\Warehouse::where('status', 'active')->orderBy('name')->get();

        if ($keyword !== '' || $warehouse_id !== '' || $zone !== '') {
            $query = ProductLocation::with([
                'product',
                'location',
                'location.warehouse',
            ])
            ->join('products',   'product_locations.product_id',  '=', 'products.id')
            ->join('locations',  'product_locations.location_id', '=', 'locations.id')
            ->leftJoin('warehouses', 'locations.warehouse_id',    '=', 'warehouses.id');

            if ($keyword !== '') {
                $like = "%{$keyword}%";
                $query->where(function ($q) use ($like) {
                    $q->where('products.name',      'like', $like)
                      ->orWhere('products.sku',      'like', $like)
                      ->orWhere('locations.zone',    'like', $like)
                      ->orWhere('locations.rack_code', 'like', $like)
                      ->orWhere('locations.pallet_code', 'like', $like);
                });
            }

            if ($warehouse_id !== '') {
                $query->where('locations.warehouse_id', $warehouse_id);
            }

            if ($zone !== '') {
                $query->where('locations.zone', $zone);
            }

            $results = $query->orderBy('warehouses.name')
                            ->orderBy('locations.zone')
                            ->orderBy('locations.rack_code')
                            ->select('product_locations.*')
                            ->get();
        }

        return view('search.location-advanced', compact('results', 'keyword', 'warehouse_id', 'zone', 'warehouses'));
    }
}
