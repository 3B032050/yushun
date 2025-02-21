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
        'service_id',
        'appointment_time',
        'payment_date',
        'service_date',
        'is_recurring',
        'status',
        'memo',
    ];

    public function scheduledetail()
    {
        return $this->hasOne(ScheduleDetail::class);
    }
    public function money()
    {
        return $this->hasOne(Money::class);
    }
    public function appointmenttime()
    {
        return $this->belongsTo(AppointmentTime::class, 'appointment_time_id');
    }
    public function master()
    {
        return $this->belongsTo(Master::class);
    }
    public function service()
    {
        return $this->belongsTo(AdminServiceItem::class, 'service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 確保外鍵名稱正確
    }
}
