<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialAndEquipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'material_name',
    ];
}
