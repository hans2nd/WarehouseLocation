<?php

namespace App\Exports;

use App\Models\WarehouseLocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return WarehouseLocation::select('location_code', 'branch', 'warehouse', 'description', 'pick_priority', 'path', 'is_active')->get();
    }

    public function headings(): array
    {
        return ['Location ID', 'Branch', 'Warehouse', 'Description', 'Pick Priority', 'Path', 'Active'];
    }
}