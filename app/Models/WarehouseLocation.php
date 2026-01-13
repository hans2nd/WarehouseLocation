<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseLocation extends Model
{
    protected $fillable = [
        'branch',
        'warehouse',
        'location_code',
        'description',
        'pick_priority',
        'path',
        'is_active',
    ];
}
