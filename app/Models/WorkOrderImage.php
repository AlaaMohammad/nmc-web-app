<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'work_order_id',
        'wo_image_path'
    ];
    public function workOrder(){
        return $this->belongsTo(WorkOrder::class);
    }
}
