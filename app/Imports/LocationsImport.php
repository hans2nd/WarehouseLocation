<?php

namespace App\Imports;

use App\Models\WarehouseLocation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Parse nilai active dengan lebih toleran terhadap berbagai format
        $activeValue = strtolower(trim($row['active'] ?? ''));
        $isActive = in_array($activeValue, ['yes', 'y', '1', 'true', 'active', 'aktif'], true) 
                    || $row['active'] === 1 
                    || $row['active'] === true;

        return new WarehouseLocation([
            // Pastikan key array sesuai dengan HEADER di file Excel (huruf kecil, spasi jadi underscore)
            'location_code' => $row['location_id'], 
            'branch'        => $row['branch'],
            'warehouse'     => $row['warehouse'],
            'description'   => $row['description'],
            'pick_priority' => $row['pick_priority'],
            'path'          => $row['path'],
            'is_active'     => $isActive,
        ]);
    }
}