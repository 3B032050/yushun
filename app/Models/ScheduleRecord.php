<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        //'master_service_area_id',
        'master_id',
        'user_id',
        'appointment_time_id',
        //'price',
        'appointment_time',
        'payment_date',
        'service_date',
        'is_recurring',
        'status',
    ];

    public function scheduledetail()
    {
        return $this->hasOne(ScheduleDetail::class);
    }
    public function appointmenttime()
    {
        return $this->belongsTo(AppointmentTime::class);
    }
    public function master()
    {
        return $this->belongsTo(Master::class);
    }
}
