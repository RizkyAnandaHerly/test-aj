<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inbound;

class TrackController extends Controller
{
    public function index(Request $request)
    {
        $orderId = $request->query('order_id');
        
        $inbound = null;
        if ($orderId) {
            // Karena kita belum memiliki tabel Ekspor/Sales Order, 
            // kita gunakan Inbound batch_no sebagai Nomor Resi untuk didemonstrasikan
            $inbound = Inbound::with(['product', 'vendor', 'qcInspection', 'receiver'])
                ->where('batch_no', $orderId)
                ->first();
        }

        return view('track.result', [
            'orderId' => $orderId,
            'inbound' => $inbound
        ]);
    }
}
