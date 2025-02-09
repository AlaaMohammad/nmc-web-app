<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'user_role',
        'action',
        'created_by'
    ];

    public function workOrder(){
        return $this->belongsTo(WorkOrder::class);
    }
}
