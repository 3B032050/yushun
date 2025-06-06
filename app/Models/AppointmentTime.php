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
        'service_address',
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

    public function borrowingRecords()  // 注意，這裡用的是複數 borrowingRecords
    {
        return $this->hasMany(BorrowingRecord::class, 'appointmenttime_id', 'id');
    }

}
