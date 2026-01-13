<?php

namespace App\Imports;

use App\Models\WarehouseLocation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new WarehouseLocation([
            // Pastikan key array sesuai dengan HEADER di file Excel (huruf kecil, spasi jadi underscore)
            'location_code' => $row['location_id'], 
            'branch'        => $row['branch'],
            'warehouse'     => $row['warehouse'],
            'description'   => $row['description'],
            'pick_priority' => $row['pick_priority'],
            'path'          => $row['path'],
            'is_active'     => ($row['active'] == 'yes' || $row['active'] == 1) ? true : false,
        ]);
    }
}