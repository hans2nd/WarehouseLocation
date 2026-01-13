<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LocationTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        // Kita berikan 1 baris contoh data dummy agar user paham formatnya
        return [
            ['AC.0.1', 'JKT-01', 'Gudang A', 'Rak A - Level 1', 10, 1, 'Yes'],
            ['AC.0.2', 'BDG-01', 'Gudang B', 'Rak A - Level 2', 20, 1, 'No'],
        ];
    }

    public function headings(): array
    {
        // Header ini akan otomatis diubah jadi 'location_id' dll oleh Laravel Excel saat import
        return [
            'Location ID',
            'Branch',
            'Warehouse',
            'Description',
            'Pick Priority',
            'Path',
            'Active',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Membuat Baris 1 (Header) menjadi Bold
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
