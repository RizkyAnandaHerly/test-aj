<?php

namespace App\Http\Controllers;

use App\Models\ShippingDocument;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShippingDocumentController extends Controller
{
    /**
     * Daftar semua dokumen pengiriman.
     */
    public function index()
    {
        $documents = ShippingDocument::with([
                        'salesOrder',
                        'creator',
                    ])
                    ->orderBy('issued_date', 'desc')
                    ->get();

        return view('shipping-documents.index', compact('documents'));
    }

    /**
     * Detail dokumen pengiriman.
     */
    public function show(ShippingDocument $shippingDocument)
    {
        $shippingDocument->load([
            'salesOrder',
            'creator',
        ]);

        return view('shipping-documents.show', compact('shippingDocument'));
    }

    /**
     * Form buat dokumen pengiriman baru.
     */
    public function create()
    {
        $salesOrders = SalesOrder::where('status', 'processing')
                                ->orderBy('order_number', 'desc')
                                ->get(['id', 'order_number', 'customer_name']);

        return view('shipping-documents.create', compact('salesOrders'));
    }

    /**
     * Simpan dokumen pengiriman baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_order_id' => ['required', 'integer', 'exists:sales_orders,id'],
            'document_type'  => ['required', 'string', 'in:suratjalan,coo,pob'],
            'issued_date'    => ['required', 'date'],
            'notes'          => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['created_by'] = Auth::id();
        $validated['document_number'] = 'DOC-' . strtoupper($validated['document_type']) . '-' . now()->format('YmdHis');
        $validated['status'] = 'issued';

        ShippingDocument::create($validated);

        return redirect()->route('shipping-documents.index')
                         ->with('success', 'Dokumen pengiriman berhasil dibuat.');
    }

    /**
     * Form edit dokumen pengiriman.
     */
    public function edit(ShippingDocument $shippingDocument)
    {
        $salesOrders = SalesOrder::where('status', 'processing')
                                ->orderBy('order_number', 'desc')
                                ->get(['id', 'order_number', 'customer_name']);

        return view('shipping-documents.edit', compact('shippingDocument', 'salesOrders'));
    }

    /**
     * Update dokumen pengiriman.
     */
    public function update(Request $request, ShippingDocument $shippingDocument)
    {
        $validator = Validator::make($request->all(), [
            'sales_order_id' => ['required', 'integer', 'exists:sales_orders,id'],
            'document_type'  => ['required', 'string', 'in:suratjalan,coo,pob'],
            'issued_date'    => ['required', 'date'],
            'status'         => ['required', 'string', 'in:draft,issued,completed,cancelled'],
            'notes'          => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $shippingDocument->update($validator->validated());

        return redirect()->route('shipping-documents.index')
                         ->with('success', 'Dokumen pengiriman berhasil diperbarui.');
    }

    /**
     * Hapus dokumen pengiriman.
     */
    public function destroy(ShippingDocument $shippingDocument)
    {
        $shippingDocument->delete();

        return redirect()->route('shipping-documents.index')
                         ->with('success', 'Dokumen pengiriman berhasil dihapus.');
    }
}
