<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ItemMovementExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function exportExcel()
    {
        // Parameter kedua adalah nama file yang akan terunduh
        return Excel::download(new ItemMovementExport, 'Laporan_Pergerakan_Barang.xlsx');
    }
}