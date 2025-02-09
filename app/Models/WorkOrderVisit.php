<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'visit_number',
        'checkin_time',
        'checked_in_by',
        'checkout_time',
        'checked_out_by',
        'duration',
    ];
}
