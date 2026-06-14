<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Product;
use App\Models\RejectItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RejectItemController extends Controller
{
    public function index()
    {
        $rejects = RejectItem::with(['inbound.product', 'product', 'inspector'])
                             ->orderByDesc('created_at')
                             ->get();

        return view('reject-items.index', compact('rejects'));
    }

    public function create()
    {
        $inbounds = Inbound::with('product')
                           ->orderByDesc('received_date')
                           ->get();

        $products = Product::where('status', 'active')
                           ->orderBy('name')
                           ->get(['id', 'name', 'sku']);

        return view('reject-items.create', compact('inbounds', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inbound_id'         => ['required', 'integer', 'exists:inbound,id'],
            'product_id'         => ['required', 'integer', 'exists:products,id'],
            'qty_rejected'       => ['required', 'integer', 'min:1'],
            'category'           => ['required', 'string', 'in:reject,quarantine'],
            'quarantine_location'=> ['nullable', 'string', 'max:255'],
            'reason'             => ['required', 'string', 'max:1000'],
        ], [
            'reason.required'   => 'Alasan reject/karantina harus diisi.',
            'qty_rejected.min'  => 'Jumlah reject harus minimal 1.',
            'category.in'       => 'Kategori harus berupa Reject atau Karantina.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->filled('inbound_id')) {
                $inbound = Inbound::find($request->input('inbound_id'));
                
                if ($inbound) {
                    // Cek kesesuaian produk dengan inbound
                    if ($request->filled('product_id') && $inbound->product_id !== (int) $request->input('product_id')) {
                        $validator->errors()->add('product_id', 'Produk harus sesuai dengan barang inbound yang dipilih.');
                    }
                    
                    // PERBAIKAN: Cek agar jumlah reject tidak melebihi jumlah inbound
                    if ($request->filled('qty_rejected') && $request->input('qty_rejected') > $inbound->qty) {
                        $validator->errors()->add('qty_rejected', 'Jumlah reject tidak boleh melebihi jumlah inbound (' . $inbound->qty . ').');
                    }
                }
            }

            // Cek ketersediaan stok global produk
            if ($request->filled('product_id') && $request->filled('qty_rejected')) {
                $product = Product::find($request->input('product_id'));
                if ($product && $request->input('qty_rejected') > $product->stock_qty) {
                    $validator->errors()->add('qty_rejected', 'Jumlah reject tidak boleh melebihi stok sistem saat ini.');
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        DB::transaction(function () use ($validated) {
            RejectItem::create([
                'inbound_id'         => $validated['inbound_id'],
                'product_id'         => $validated['product_id'],
                'inspector_id'       => Auth::id(),
                'qty_rejected'       => $validated['qty_rejected'],
                'category'           => $validated['category'],
                'quarantine_location'=> $validated['quarantine_location'] ?? null,
                'reason'             => $validated['reason'],
            ]);

            $product = Product::findOrFail($validated['product_id']);
            $product->decrement('stock_qty', $validated['qty_rejected']);
        });

        return redirect()->route('reject-items.index')
                         ->with('success', 'Data reject/karantina berhasil disimpan dan stok layak diperbarui.');
    }
}
