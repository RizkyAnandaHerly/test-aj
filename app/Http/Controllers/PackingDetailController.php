<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\PackingDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackingDetailController extends Controller
{
    public function index()
    {
        $packings = PackingDetail::with(['inbound.product', 'product', 'packer'])
                                 ->orderByDesc('created_at')
                                 ->get();

        return view('packing-details.index', compact('packings'));
    }

    public function create()
    {
        $inbounds = Inbound::with('product')
                           ->orderByDesc('received_date')
                           ->get();

        $products = Product::where('status', 'active')
                           ->orderBy('name')
                           ->get(['id', 'name', 'sku']);

        return view('packing-details.create', compact('inbounds', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inbound_id'         => ['required', 'integer', 'exists:inbound,id'],
            'product_id'         => ['required', 'integer', 'exists:products,id'],
            'quantity'           => ['required', 'integer', 'min:1'],
            'packaging_type'     => ['required', 'string', 'max:100'],
            'package_weight'     => ['nullable', 'string', 'max:50'],
            'package_dimensions' => ['nullable', 'string', 'max:100'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ], [
            'quantity.min'        => 'Jumlah packing minimal 1.',
            'packaging_type.required' => 'Tipe packaging harus diisi.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->filled('inbound_id')) {
                $inbound = Inbound::find($request->input('inbound_id'));
                
                if ($inbound) {
                    // Cek kesesuaian produk dengan inbound
                    if ($request->filled('product_id') && $inbound->product_id !== (int) $request->input('product_id')) {
                        $validator->errors()->add('product_id', 'Produk harus sesuai dengan inbound yang dipilih.');
                    }
                    
                    // PERBAIKAN: Cek agar jumlah packing tidak melebihi jumlah inbound
                    if ($request->filled('quantity') && $request->input('quantity') > $inbound->qty) {
                        $validator->errors()->add('quantity', 'Jumlah packing tidak boleh melebihi jumlah inbound (' . $inbound->qty . ').');
                    }
                }
            }
            // Cek ketersediaan stok global produk
            if ($request->filled('product_id') && $request->filled('quantity')) {
                $product = Product::find($request->input('product_id'));
                if ($product && $request->input('quantity') > $product->stock_qty) {
                    $validator->errors()->add('quantity', 'Jumlah packing tidak boleh melebihi stok layak saat ini.');
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        DB::transaction(function () use ($validated, &$packing) {
            $product = Product::findOrFail($validated['product_id']);
            $labelCode = sprintf('PKG-%s-%s', strtoupper($product->sku), now()->format('YmdHis'));

            $packing = PackingDetail::create([
                'inbound_id'         => $validated['inbound_id'],
                'product_id'         => $validated['product_id'],
                'packer_id'          => Auth::id(),
                'quantity'           => $validated['quantity'],
                'packaging_type'     => $validated['packaging_type'],
                'package_weight'     => $validated['package_weight'] ?? null,
                'package_dimensions' => $validated['package_dimensions'] ?? null,
                'label_code'         => $labelCode,
                'notes'              => $validated['notes'] ?? null,
                'label_printed_at'   => now(),
            ]);

            $product->decrement('stock_qty', $validated['quantity']);
        });

        return redirect()->route('packing-details.show', $packing)
                         ->with('success', 'Detail packing berhasil disimpan. Label siap dicetak.');
    }

    public function show(PackingDetail $packingDetail)
    {
        $packingDetail->load(['inbound.product', 'product', 'packer']);

        return view('packing-details.show', compact('packingDetail'));
    }
}
