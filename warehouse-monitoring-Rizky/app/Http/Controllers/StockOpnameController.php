<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\StockAdjustmentLog;
use App\Models\Warehouse;
use App\Models\ProductLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockOpnameController extends Controller
{
    /**
     * Daftar semua stock opname.
     */
    public function index()
    {
        $opnames = StockOpname::with([
                        'warehouse',
                        'creator',
                    ])
                    ->orderBy('opname_date', 'desc')
                    ->get();

        return view('stock-opnames.index', compact('opnames'));
    }

    /**
     * Detail stock opname dengan semua detailnya.
     */
    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load([
            'warehouse',
            'creator',
            'details.product',
            'details.productLocation.location',
            'adjustmentLogs.product',
            'adjustmentLogs.location',
        ]);

        return view('stock-opnames.show', compact('stockOpname'));
    }

    /**
     * Form buat stock opname baru.
     */
    public function create()
    {
        $warehouses = Warehouse::where('status', 'active')
                              ->orderBy('name')
                              ->get(['id', 'name', 'code']);

        return view('stock-opnames.create', compact('warehouses'));
    }

    /**
     * Simpan stock opname baru (header saja).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'opname_date'  => ['required', 'date'],
            'notes'        => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['created_by'] = Auth::id();
        $validated['opname_number'] = 'OPNAME-' . now()->format('YmdHis');
        $validated['status'] = 'in_progress';

        $opname = StockOpname::create($validated);

        return redirect()->route('stock-opnames.edit', $opname->id)
                         ->with('success', 'Stock opname berhasil dibuat. Silakan masukkan data perhitungan fisik.');
    }

    /**
     * Form edit stock opname (input detail perhitungan fisik).
     */
    public function edit(StockOpname $stockOpname)
    {
        // Load product locations dari warehouse
        $productLocations = ProductLocation::with(['product', 'location'])
                                          ->whereHas('location', function ($q) use ($stockOpname) {
                                              $q->where('warehouse_id', $stockOpname->warehouse_id);
                                          })
                                          ->get();

        // Load existing details
        $stockOpname->load('details.product');

        return view('stock-opnames.edit', compact('stockOpname', 'productLocations'));
    }

    /**
     * Update detail stock opname dan hitung selisih stok.
     */
    public function updateDetail(Request $request, StockOpname $stockOpname)
    {
        $validator = Validator::make($request->all(), [
            'product_location_id' => ['required', 'integer', 'exists:product_locations,id'],
            'physical_qty'        => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $productLocation = ProductLocation::findOrFail($validated['product_location_id']);

        // Hitung perbedaan
        $system_qty = $productLocation->qty;
        $physical_qty = $validated['physical_qty'];
        $difference = $physical_qty - $system_qty;

        // Check if detail already exists
        $detail = StockOpnameDetail::where('stock_opname_id', $stockOpname->id)
                                   ->where('product_location_id', $productLocation->id)
                                   ->first();

        if ($detail) {
            $detail->update([
                'system_qty'   => $system_qty,
                'physical_qty' => $physical_qty,
                'difference'   => $difference,
            ]);
        } else {
            StockOpnameDetail::create([
                'stock_opname_id'    => $stockOpname->id,
                'product_location_id' => $productLocation->id,
                'product_id'         => $productLocation->product_id,
                'system_qty'         => $system_qty,
                'physical_qty'       => $physical_qty,
                'difference'         => $difference,
            ]);
        }

        return redirect()->route('stock-opnames.edit', $stockOpname->id)
                         ->with('success', 'Data perhitungan fisik berhasil disimpan.');
    }

    /**
     * Ajukan hasil perhitungan stock opname untuk diterapkan atau ditinjau jika ada selisih.
     */
    public function apply(Request $request, StockOpname $stockOpname)
    {
        if ($stockOpname->status !== 'in_progress') {
            return back()->with('error', 'Stock opname hanya bisa diajukan jika masih dalam status In Progress.');
        }

        // Cek apakah ada detail opname yang memiliki perbedaan (selisih)
        $hasDifference = StockOpnameDetail::where('stock_opname_id', $stockOpname->id)
                                          ->where('difference', '!=', 0)
                                          ->exists();

        if ($hasDifference) {
            // Jika ada selisih, ganti status ke pending_adjustment untuk ditinjau Manager/Admin
            $stockOpname->update([
                'status' => 'pending_adjustment'
            ]);

            return redirect()->route('stock-opnames.show', $stockOpname->id)
                             ->with('warning', 'Terdeteksi selisih stok. Stock opname diajukan ke Manager/Admin untuk persetujuan penyesuaian.');
        } else {
            // Jika tidak ada selisih, langsung selesaikan tanpa adjustment log
            $stockOpname->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            return redirect()->route('stock-opnames.show', $stockOpname->id)
                             ->with('success', 'Stock opname selesai. Seluruh stok fisik sesuai dengan sistem.');
        }
    }

    /**
     * Setujui & Terapkan penyesuaian stok (Hanya untuk Admin/Manager).
     */
    public function approveAdjustment(Request $request, StockOpname $stockOpname)
    {
        // Pastikan role berwenang
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('manager')) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk menyetujui penyesuaian stok.');
        }

        if ($stockOpname->status !== 'pending_adjustment') {
            return back()->with('error', 'Hanya stock opname berstatus Pending Adjustment yang dapat disetujui.');
        }

        $details = StockOpnameDetail::with('productLocation.location')->where('stock_opname_id', $stockOpname->id)->get();

        DB::transaction(function () use ($stockOpname, $details) {
            foreach ($details as $detail) {
                if ($detail->difference !== 0) {
                    $product = $detail->product;
                    $productLocation = $detail->productLocation;
                    $adjustment_qty = abs($detail->difference);
                    $adjustment_type = $detail->difference > 0 ? 'increase' : 'decrease';

                    // 1. Update stock global produk
                    if ($adjustment_type === 'increase') {
                        $product->increment('stock_qty', $adjustment_qty);
                    } else {
                        $product->decrement('stock_qty', $adjustment_qty);
                    }

                    // 2. Update stock spesifik di level lokasi penyimpanan
                    if ($productLocation) {
                        $productLocation->qty_stored = $detail->physical_qty;
                        $productLocation->save();

                        // 3. Update status kapasitas lokasi rak
                        $location = $productLocation->location;
                        if ($location) {
                            $totalLocationQty = ProductLocation::where('location_id', $location->id)->sum('qty_stored');
                            $location->update([
                                'status' => match (true) {
                                    $totalLocationQty >= $location->capacity => 'full',
                                    $totalLocationQty > 0                   => 'reserved',
                                    default                                 => 'available',
                                },
                            ]);
                        }
                    }

                    // 4. Log adjustment
                    StockAdjustmentLog::create([
                        'stock_opname_id'  => $stockOpname->id,
                        'product_id'       => $product->id,
                        'location_id'      => $productLocation ? $productLocation->location_id : 1, // fallback ke default jika kosong
                        'adjustment_qty'   => $adjustment_qty,
                        'adjustment_type'  => $adjustment_type,
                        'reason'           => 'Penyesuaian disetujui dari Stock Opname',
                    ]);
                }
            }

            // Mark opname as completed
            $stockOpname->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        });

        return redirect()->route('stock-opnames.show', $stockOpname->id)
                         ->with('success', 'Penyesuaian stok berhasil disetujui dan diterapkan.');
    }

    /**
     * Tolak penyesuaian stok dan kembalikan ke staff untuk hitung ulang.
     */
    public function rejectAdjustment(Request $request, StockOpname $stockOpname)
    {
        // Pastikan role berwenang
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('manager')) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk menolak penyesuaian stok.');
        }

        if ($stockOpname->status !== 'pending_adjustment') {
            return back()->with('error', 'Hanya stock opname berstatus Pending Adjustment yang dapat ditolak.');
        }

        $stockOpname->update([
            'status' => 'in_progress',
            'notes' => ($stockOpname->notes ? $stockOpname->notes . "\n" : "") . "[Penyesuaian ditolak oleh " . Auth::user()->name . " pada " . now()->format('d/m/Y H:i') . "]"
        ]);

        return redirect()->route('stock-opnames.edit', $stockOpname->id)
                         ->with('info', 'Penyesuaian ditolak. Status dikembalikan ke In Progress agar dapat dihitung ulang.');
    }

    /**
     * Batalkan stock opname.
     */
    public function cancel(StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'completed') {
            return back()->with('error', 'Tidak bisa membatalkan stock opname yang sudah selesai.');
        }

        $stockOpname->update(['status' => 'cancelled']);

        return redirect()->route('stock-opnames.index')
                         ->with('success', 'Stock opname berhasil dibatalkan.');
    }
}
