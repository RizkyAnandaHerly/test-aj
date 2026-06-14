<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Inbound;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::with(['inbound.product', 'product', 'certifier'])
                                       ->orderByDesc('created_at')
                                       ->get();

        return view('certifications.index', compact('certifications'));
    }

    public function create()
    {
        $inbounds = Inbound::with('product')
                           ->orderByDesc('received_date')
                           ->get();

        $products = Product::where('status', 'active')
                           ->orderBy('name')
                           ->get(['id', 'name', 'sku']);

        return view('certifications.create', compact('inbounds', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inbound_id'         => ['required', 'integer', 'exists:inbound,id'],
            'product_id'         => ['required', 'integer', 'exists:products,id'],
            'certification_date' => ['required', 'date'],
            'certification_type' => ['required', 'string', 'max:100'],
            'lot_number'         => ['required', 'string', 'max:100'],
            'standard_region'    => ['required', 'string', 'max:50'],
            'document'           => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ], [
            'document.mimes' => 'Dokumen sertifikasi harus berformat PDF, JPG, JPEG, atau PNG.',
            'document.max'   => 'Dokumen sertifikasi maksimal 10 MB.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->filled('inbound_id') && $request->filled('product_id')) {
                $inbound = Inbound::find($request->input('inbound_id'));
                if ($inbound && $inbound->product_id !== (int) $request->input('product_id')) {
                    $validator->errors()->add('product_id', 'Produk harus sesuai dengan inbound yang dipilih.');
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        
        // Simpan file langsung ke folder public/uploads/certifications agar terhindar dari isu symlink storage:link yang mati
        $file = $request->file('document');
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->move(public_path('uploads/certifications'), $filename);
        $documentPath = 'uploads/certifications/' . $filename;
        
        $status = $validated['standard_region'] === 'Eropa' ? 'valid' : 'pending';

        $certification = Certification::create([
            'inbound_id'         => $validated['inbound_id'],
            'product_id'         => $validated['product_id'],
            'certifier_id'       => Auth::id(),
            'certification_date' => $validated['certification_date'],
            'certification_type' => $validated['certification_type'],
            'lot_number'         => $validated['lot_number'],
            'standard_region'    => $validated['standard_region'],
            'document_path'      => $documentPath,
            'document_name'      => $file->getClientOriginalName(),
            'status'             => $status,
            'notes'              => $validated['notes'] ?? null,
        ]);

        return redirect()->route('certifications.show', $certification)
                         ->with('success', 'Data sertifikasi berhasil disimpan.');
    }

    public function show(Certification $certification)
    {
        $certification->load(['inbound.product', 'product', 'certifier']);

        return view('certifications.show', compact('certification'));
    }
}
