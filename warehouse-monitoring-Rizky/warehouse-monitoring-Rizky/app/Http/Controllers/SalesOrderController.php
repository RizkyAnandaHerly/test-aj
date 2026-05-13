<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\QcInspection;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Form QC untuk kiriman tertentu
     */
    public function create(Request $request)
    {
        return view('sales-order.create');
    }

    public function store(Request $request)
    {
        // Logic simpan hasil QC & Parameter...
    }
}