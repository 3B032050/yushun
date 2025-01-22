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
        'start_time',
        'end_time',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedulerecord()
    {
        return $this->hasOne(ScheduleRecord::class, 'appointment_time_id');
    }

    public function master()
    {
        return $this->belongsTo(Master::class, 'master_id');
    }

}
