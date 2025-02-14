<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number',
        'client_id',
        'service_id',
        'service_category_id',
        'approval_status',
        'assignment_status',
        'assigned_to',
        'status',
        'technician_id',
        'scheduled_date',
        'scheduled_time',
        'completion_date',
        'completion_time',
        'client_description',
        'scope',
        'priority',
        'longitude',
        'latitude',

    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'assigned_to');
    }

    public function images()
    {
        return $this->hasMany(WorkOrderImage::class);
    }

    public function clientInvoice()
    {
        return $this->hasOne(ClientInvoice::class);
    }

    public function workOrderLogs()
    {
        return $this->hasMany(WorkOrderLog::class);
    }

    public function workOrderRequest()
    {
        return $this->hasMany(WorkOrderRequest::class);
    }

    public function materials(){
           return $this->belongsToMany(MaterialAndEquipment::class);

        }

    public function workOrderVisits(){
            return $this->hasMany(WorkOrderVisit::class);
    }
    public function service(){
            return $this->belongsTo(Service::class);
    }
    public function serviceCategory()
    {
    return $this->belongsTo(ServiceCategory::class);
    }
    public function invoice(){
    return $this->hasOne(Invoice::class);
    }


}
