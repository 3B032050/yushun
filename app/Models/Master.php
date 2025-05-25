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
        'introduction',
        'position',
        'total_hours',
    ];

    protected $hidden = ['password', 'remember_token'];
    public function isMaster()
    {
        return true;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function serviceAreas()
    {
        return $this->hasMany(MasterServiceArea::class, 'master_id');
    }
//    public function serviceitem()
//    {
//        return $this->belongsToMany(AdminServiceItem::class, 'item_master_relationship', 'service_item_id', 'master_id');
//    }
//
    public function appointmenttime()
    {
        return $this->hasOne(AppointmentTime::class, 'master_id');
    }


    public function rentuniforms()
    {
        return $this->hasMany(RentUniform::class, 'master_id');
    }
    public function ScheduleRecord()
    {
        return $this->hasMany(ScheduleRecord::class); // 假設師傅有多個項目
    }

    public function borrowingRecord()
    {
        return $this->hasMany(BorrowingRecord::class); // 假設師傅有多個項目
    }

    public function money()
    {
        return $this->hasOne(Money::class);
    }

}
