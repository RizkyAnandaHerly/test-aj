<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder; // Pastikan memanggil model SalesOrder, bukan Inbound lagi

class TrackController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan input pencarian (bisa no Sales Order atau Kode Packing PKG-)
        $search = $request->query('search') ?? $request->query('order_id');
        
        $salesOrder = null;
        $packingDetail = null;
        $type = 'so'; // default

        if ($search) {
            $searchUpper = strtoupper(trim($search));
            if (str_starts_with($searchUpper, 'PKG-')) {
                $type = 'packing';
                $packingDetail = \App\Models\PackingDetail::with([
                    'product.certifications',
                    'packer',
                    'inbound.vendor',
                    'inbound.qcInspection.inspector',
                    'product.productLocations.location'
                ])->where('label_code', $searchUpper)->first();
            } else {
                $salesOrder = SalesOrder::where('order_number', $search)->first();
            }
        }

        return view('track.result', [
            'orderId'       => $search, // tetapkan kompatibilitas variabel orderId
            'search'        => $search,
            'salesOrder'    => $salesOrder,
            'packingDetail' => $packingDetail,
            'type'          => $type
        ]);
    }
}