<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan Katalog Barang dengan search, filter, dan pagination.
     *
     * Query params:
     *   ?search=   — cari berdasarkan nama ATAU sku
     *   ?status=   — filter: active | inactive | (kosong = semua)
     *   ?category= — filter berdasarkan kategori
     */
    public function index(Request $request)
    {
        $query = Product::query()->orderBy('created_at', 'desc');

        // ── Search (nama atau SKU) ────────────────────────────────────────────
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku',  'like', "%{$search}%");
            });
        }

        // ── Filter status ─────────────────────────────────────────────────────
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // ── Filter kategori ───────────────────────────────────────────────────
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $products   = $query->paginate(10)->withQueryString();
        $categories = Product::distinct()->orderBy('category')->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan detail satu produk beserta sertifikasinya.
     */
    public function show(Product $product)
    {
        $product->load(['certifications.certifier']);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'sku'         => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name'        => ['required', 'string', 'max:255'],
            'category'    => ['nullable', 'string', 'max:255'],
            'unit'        => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'min_stock'   => ['required', 'integer', 'min:0'],
            'status'      => ['required', 'in:active,inactive'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ], [
            'sku.unique' => 'SKU produk sudah digunakan. Gunakan SKU lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
                         ->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'sku'         => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'name'        => ['required', 'string', 'max:255'],
            'category'    => ['nullable', 'string', 'max:255'],
            'unit'        => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'min_stock'   => ['required', 'integer', 'min:0'],
            'status'      => ['required', 'in:active,inactive'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ], [
            'sku.unique' => 'SKU produk sudah digunakan. Gunakan SKU lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
                         ->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}
