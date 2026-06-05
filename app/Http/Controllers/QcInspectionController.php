<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Product;
use App\Models\QcInspection;
use App\Models\QcParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QcInspectionController extends Controller
{
    /**
     * Daftar semua hasil QC dengan eager load lengkap.
     */
    public function index()
    {
        $inspections = QcInspection::with([
                            'inbound',
                            'product',
                            'inspector',
                            'parameters',
                        ])
                        ->orderBy('inspection_date', 'desc')
                        ->get();

        return view('qc-inspections.index', compact('inspections'));
    }

    /**
     * Detail hasil QC — Fix: route 'show' was missing (404).
     */
    public function show(QcInspection $qcInspection)
    {
        $qcInspection->load([
            'inbound',
            'inbound.vendor',
            'product',
            'inspector',
            'parameters',
        ]);

        return view('qc-inspections.show', compact('qcInspection'));
    }

    /**
     * Form input hasil QC.
     *
     * Passes:
     *   $inbounds — semua record inbound dengan produknya (untuk dropdown)
     *   $products — produk aktif saja (untuk dropdown produk)
     */
    public function create()
    {
        $inbounds = Inbound::with('product')
                           ->orderBy('received_date', 'desc')
                           ->get();

        $products = Product::where('status', 'active')
                           ->orderBy('name')
                           ->get(['id', 'name', 'sku']);

        return view('qc-inspections.create', compact('inbounds', 'products'));
    }

    /**
     * Simpan hasil QC (qc_inspections) + semua parameter (qc_parameters).
     *
     * CRITICAL RULES:
     *   - inspector_id  → Auth::id() only, NEVER from form input
     *   - status        → auto-computed from parameter result[] values
     *   - All DB writes → wrapped in a single DB::transaction()
     */
    public function store(Request $request)
    {
        // ── 1. Validate ───────────────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'inbound_id'        => ['required', 'integer', 'exists:inbound,id'],
            'product_id'        => ['required', 'integer', 'exists:products,id'],
            'inspection_date'   => ['required', 'date'],
            'notes'             => ['nullable', 'string'],

            // Parameter arrays — minimum 1 row required
            'parameter_name'    => ['required', 'array', 'min:1'],
            'parameter_name.*'  => ['required', 'string', 'max:255'],
            'expected_value'    => ['required', 'array', 'min:1'],
            'expected_value.*'  => ['required', 'string', 'max:255'],
            'actual_value'      => ['required', 'array', 'min:1'],
            'actual_value.*'    => ['required', 'string', 'max:255'],
            'result'            => ['required', 'array', 'min:1'],
            'result.*'          => ['required', 'string', 'in:pass,fail'],
        ], [
            'parameter_name.required'   => 'Minimal satu parameter inspeksi harus diisi.',
            'parameter_name.min'        => 'Minimal satu parameter inspeksi harus diisi.',
            'parameter_name.*.required' => 'Nama parameter tidak boleh kosong.',
            'expected_value.*.required' => 'Nilai ekspektasi tidak boleh kosong.',
            'actual_value.*.required'   => 'Nilai aktual tidak boleh kosong.',
            'result.*.in'               => 'Hasil parameter harus pass atau fail.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // ── 2. Auto-compute status from parameter results ─────────────────────
        $results   = $validated['result'];
        $passCount = count(array_filter($results, fn($r) => $r === 'pass'));
        $failCount = count(array_filter($results, fn($r) => $r === 'fail'));
        $total     = count($results);

        if ($passCount === $total) {
            $status = 'pass';
        } elseif ($failCount === $total) {
            $status = 'fail';
        } else {
            $status = 'partial';
        }

        // ── 3. Persist everything atomically ──────────────────────────────────
        DB::transaction(function () use ($validated, $status) {

            // 3a. Create the QC inspection header
            $inspection = QcInspection::create([
                'inbound_id'      => $validated['inbound_id'],
                'product_id'      => $validated['product_id'],
                'inspector_id'    => Auth::id(),          // NEVER from form
                'inspection_date' => $validated['inspection_date'],
                'status'          => $status,             // auto-computed
                'notes'           => $validated['notes'] ?? null,
            ]);

            // 3b. Create one QcParameter row per submitted parameter
            $count = count($validated['parameter_name']);

            for ($i = 0; $i < $count; $i++) {
                QcParameter::create([
                    'qc_inspection_id' => $inspection->id,
                    'parameter_name'   => $validated['parameter_name'][$i],
                    'expected_value'   => $validated['expected_value'][$i],
                    'actual_value'     => $validated['actual_value'][$i],
                    'result'           => $validated['result'][$i],
                ]);
            }
        });

        // ── 4. Redirect with flash ────────────────────────────────────────────
        return redirect()->route('qc-inspections.index')
                         ->with('success', 'Data QC berhasil disimpan');
    }
}