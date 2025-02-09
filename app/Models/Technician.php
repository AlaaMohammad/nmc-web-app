<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Technician extends Authenticatable
{
    use HasFactory,hasapitokens;

    protected $fillable = [
        'full_name',
        'phone_number',
        'state',
        'email',
        'password',
        'phone_verification_code',
    ];

    public function  workOrders(){
        return $this->hasMany(WorkOrder::class,'assigned_to');
    }
}
