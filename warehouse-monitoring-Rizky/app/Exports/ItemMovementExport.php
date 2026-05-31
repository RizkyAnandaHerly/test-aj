<?php

namespace App\Exports;

use App\Models\ActivityLog; // <-- GANTI INI JIKA NAMA MODELMU BERBEDA (misal: use App\Models\Log;)
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemMovementExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Mengambil data dari database
    */
    public function collection()
    {
        // Mengambil semua log aktivitas beserta relasi user-nya
        // Pastikan 'user' adalah nama relasi di model ActivityLog milikmu
        return ActivityLog::with('user')->latest()->get(); 
    }

    /**
    * Menentukan nama kolom (Header) di baris pertama Excel
    */
    public function headings(): array
    {
        return [
            'Waktu',
            'Pengguna',
            'Role',
            'Aksi',
            'Model Terkait',
            'ID Model',
            'Keterangan',
        ];
    }

    /**
    * Memetakan data dari database ke dalam baris Excel
    */
    
    public function map($log): array
    {
        return [
            $log->created_at->format('d M Y H:i:s'),
            $log->user->name ?? '(deleted)',
            $log->role_name ?? '-',
            strtoupper($log->action),
            $log->model_type,
            $log->model_id,
            $log->description,
        ];
    }
}