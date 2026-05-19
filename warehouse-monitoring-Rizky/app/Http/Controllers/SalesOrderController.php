<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::latest()->paginate(10);
        // Mengubah sales_orders menjadi sales_order sesuai nama folder Anda
        return view('sales_order.index', compact('salesOrders'));
    }

    public function create()
    {
        // Mengubah sales_orders menjadi sales_order
        return view('sales_order.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required|unique:sales_orders,order_number',
            'customer_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'description' => 'nullable|string',
            'origin_country' => 'required|string|max:100',
            'destination_country' => 'required|string|max:100',
        ]);

        SalesOrder::create($request->all());

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order berhasil ditambahkan.');
    }

    public function show(SalesOrder $salesOrder)
    {
        // Memperbaiki pemanggilan view menjadi nama_folder.nama_file
        return view('sales_order.show', compact('salesOrder'));
    }

    public function edit(SalesOrder $salesOrder)
    {
        // Mengubah sales_orders menjadi sales_order
        return view('sales_order.edit', compact('salesOrder'));
    }

    public function update(Request $request, SalesOrder $salesOrder)
    {
        $request->validate([
            'order_number' => 'required|unique:sales_orders,order_number,' . $salesOrder->id,
            'customer_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'description' => 'nullable|string',
            'origin_country' => 'required|string|max:100',
            'destination_country' => 'required|string|max:100',
        ]);

        $salesOrder->update($request->all());

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order berhasil diperbarui.');
    }

    public function destroy(SalesOrder $salesOrder)
    {
        $salesOrder->delete();

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order berhasil dihapus.');
    }
}