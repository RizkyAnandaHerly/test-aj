<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder; // Pastikan memanggil model SalesOrder, bukan Inbound lagi

class TrackController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan input pencarian (misal dari form <input name="order_id">)
        $orderId = $request->query('search') ?? $request->query('order_id');
        
        $salesOrder = null;
        if ($orderId) {
            $salesOrder = SalesOrder::where('order_number', $orderId)->first();
        }

        return view('track.result', [
            'orderId'    => $orderId,
            'salesOrder' => $salesOrder
        ]);

        // Kirim data SO ke view
        return view('track.result', [
            'orderId'    => $orderId,
            'salesOrder' => $salesOrder // Variabel diubah dari 'inbound' menjadi 'salesOrder'
        ]);
    }
}