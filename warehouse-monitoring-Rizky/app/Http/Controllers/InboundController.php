<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use App\Models\Inbound;
use App\Models\ProductLocation;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InboundController extends Controller
{
    /**
     * Form Input Barang Masuk (updated with vendor dropdown + cascading location).
     */
    public function create()
    {
        $products = Product::where('status', 'active')
                            ->orderBy('name', 'asc')
                            ->get(['id', 'name', 'sku']);

        $vendors = Vendor::where('status', 'active')
                          ->orderBy('name', 'asc')
                          ->get(['id', 'name', 'code']);

        $warehouses = Warehouse::where('status', 'active')
                                ->orderBy('name', 'asc')
                                ->get(['id', 'name', 'code']);

        return view('inbounds.create', compact('products', 'vendors', 'warehouses'));
    }

    /**
     * Simpan data penerimaan barang (inbound) ke database.
     *
     * received_by di-inject dari Auth::id() — TIDAK dari form input.
     * vendor_id dari dropdown (menggantikan supplier text).
     * Setelah simpan, stock_qty produk langsung di-increment.
     */
    public function store(Request $request)
    {
        // ── 1. Validate ───────────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'product_id'    => ['required', 'integer', 'exists:products,id'],
            'qty'           => ['required', 'integer', 'min:1'],
            'vendor_id'     => ['nullable', 'integer', 'exists:vendors,id'],
            'location_id'   => ['nullable', 'integer', 'exists:locations,id'],
            'batch_no'      => ['nullable', 'string', 'max:255'],
            'received_date' => ['required', 'date'],
            'notes'         => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (! empty($validated['location_id'])) {
            $location = Location::findOrFail($validated['location_id']);
            $currentQty = ProductLocation::where('location_id', $location->id)
                                         ->sum('qty_stored');

            if (($currentQty + $validated['qty']) > $location->capacity) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'location_id' => 'Kapasitas lokasi tidak mencukupi untuk jumlah inbound ini.',
                    ]);
            }
        }

        // ── 2. Inject received_by from session (NEVER from form) ──────────────
        $validated['received_by'] = Auth::id();

        // ── 3 & 4. Persist inbound + increment stock (atomic) ───────────────
        DB::transaction(function () use ($validated) {
            Inbound::create($validated);

            $product = Product::findOrFail($validated['product_id']);
            $product->increment('stock_qty', $validated['qty']);

            if (! empty($validated['location_id'])) {
                $location = Location::findOrFail($validated['location_id']);

                $placement = ProductLocation::where('product_id', $validated['product_id'])
                                            ->where('location_id', $validated['location_id'])
                                            ->first();

                if ($placement) {
                    $placement->qty_stored += (int) $validated['qty'];
                    $placement->save();
                } else {
                    ProductLocation::create([
                        'product_id'  => $validated['product_id'],
                        'location_id' => $validated['location_id'],
                        'qty_stored'  => (int) $validated['qty'],
                    ]);
                }

                $totalQty = ProductLocation::where('location_id', $location->id)
                                           ->sum('qty_stored');

                $location->update([
                    'status' => match (true) {
                        $totalQty >= $location->capacity => 'full',
                        $totalQty > 0                   => 'reserved',
                        default                         => 'available',
                    },
                ]);
            }
        });

        // ── 5. Redirect with flash ────────────────────────────────────────────
        return redirect()->route('inbounds.create')
                         ->with('success', 'Barang berhasil dicatat.');
    }
}