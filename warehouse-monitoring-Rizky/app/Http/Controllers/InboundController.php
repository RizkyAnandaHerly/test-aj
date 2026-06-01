<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inbound;
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
            'qty'           => ['required', 'integer', 'min:1', 'max:1000000000'],
            'vendor_id'     => ['nullable', 'integer', 'exists:vendors,id'],
            'batch_no'      => ['nullable', 'string', 'max:255'],
            'received_date' => ['required', 'date'],
            'notes'         => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // ── 2. Inject received_by from session (NEVER from form) ──────────────
        $validated['received_by'] = Auth::id();

        // ── 3 & 4. Persist inbound + increment stock (atomic) ───────────────
        DB::transaction(function () use ($validated) {
            Inbound::create($validated);

            $product = Product::findOrFail($validated['product_id']);
            $product->increment('stock_qty', $validated['qty']);
        });

        // ── 5. Redirect with flash ────────────────────────────────────────────
        return redirect()->route('inbounds.create')
                         ->with('success', 'Barang berhasil dicatat.');
    }
}