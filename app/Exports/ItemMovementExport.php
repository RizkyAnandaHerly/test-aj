<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemMovementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Build filtered query and return collection.
     */
    public function collection(): Collection
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['action'])) {
            $query->where('action', $this->filters['action']);
        }

        if (!empty($this->filters['model_type'])) {
            $query->where('model_type', $this->filters['model_type']);
        }

        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        return $query->get();
    }

    /**
     * Column headings for the first row.
     */
    public function headings(): array
    {
        return [
            'No',
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
     * Map each row to an array of cell values.
     */
    public function map($log): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $log->created_at->format('d M Y H:i:s'),
            $log->user->name ?? '(deleted)',
            $log->role_name ?? '-',
            strtoupper($log->action),
            $log->model_type,
            $log->model_id,
            $log->description,
        ];
    }

    /**
     * Style the header row.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Sheet title.
     */
    public function title(): string
    {
        return 'Laporan Pergerakan Barang';
    }
}