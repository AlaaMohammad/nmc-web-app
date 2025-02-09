<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderMaterial extends Model
{
    use HasFactory;
    protected $fillable = [
        'material_name',
        'quantity',
        'work_order_id'
    ];
}
