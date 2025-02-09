<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Authenticatable {
    use HasFactory;


    protected $fillable = [
        'name', 'email', 'password',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    public function clients() {
        return $this->hasMany(Client::class);
    }

    public function workOrders() {
        return $this->hasMany(WorkOrder::class);
    }



}
