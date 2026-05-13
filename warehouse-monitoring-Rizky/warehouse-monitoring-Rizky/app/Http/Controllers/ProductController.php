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
     * Menampilkan detail satu produk.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
