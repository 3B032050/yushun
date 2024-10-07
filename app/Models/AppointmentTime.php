<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'master_id',
        'service_date',
        'time_period',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedulerecord()
    {
        return $this->hasMany(ScheduleRecord::class,'appointment_time_id');
    }
    public function master()
    {
        return $this->belongsToMany(Master::class, 'master_appointment_relationship', 'master_id', 'appointment_time_id');
    }

}
