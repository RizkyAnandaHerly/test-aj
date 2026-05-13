<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::orderBy('name')->get();

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'code'     => ['required', 'string', 'max:50', 'unique:warehouses,code'],
            'address'  => ['nullable', 'string'],
            'pic_name' => ['nullable', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'status'   => ['required', 'in:active,inactive'],
        ], [
            'code.unique' => 'Kode gudang sudah digunakan. Gunakan kode lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Warehouse::create($validator->validated());

        return redirect()->route('warehouses.index')
                         ->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'code'     => ['required', 'string', 'max:50', 'unique:warehouses,code,' . $warehouse->id],
            'address'  => ['nullable', 'string'],
            'pic_name' => ['nullable', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'status'   => ['required', 'in:active,inactive'],
        ], [
            'code.unique' => 'Kode gudang sudah digunakan. Gunakan kode lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $warehouse->update($validator->validated());

        return redirect()->route('warehouses.index')
                         ->with('success', 'Data gudang berhasil diperbarui.');
    }
}
