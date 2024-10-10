<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // 確保引入此類
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Master extends Authenticatable // 修改這裡
{
    use Notifiable;
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'position',
    ];

    protected $hidden = ['password', 'remember_token'];
    public function isMaster()
    {
        return true;
    }
//    public function serviceAreas()
//    {
//        return $this->hasMany(MasterServiceArea::class, 'master_id');
//    }
//    public function serviceitem()
//    {
//        return $this->belongsToMany(AdminServiceItem::class, 'item_master_relationship', 'service_item_id', 'master_id');
//    }
//
//    public function appointmenttime()
//    {
//        return $this->belongsToMany(AppointmentTime::class, 'master_appointment_relationship', 'appointment_time_id', 'master_id');
//    }
}
