<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // 確保引入此類
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Master extends Authenticatable // 修改這裡
{
    use Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'position',
        'service_item_id',
        'service_area_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function serviceitem()
    {
        return $this->belongsToMany(Serviceitem::class, 'item_master_relationship', 'service_item_id', 'master_id');
    }

    public function appointmenttime()
    {
        return $this->belongsToMany(Appointmenttime::class, 'master_appointment_relationship', 'appointmenttime_id', 'master_id');
    }
}
