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
}
