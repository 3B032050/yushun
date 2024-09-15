<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedulerecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'master_id',
        'user_id',
        'appointmenttime_id',
        'price',
        'time_period',
        'payment_date',
        'service_date',
    ];

    public function scheduledetail()
    {
        return $this->hasOne(Scheduledetail::class);
    }
    public function appointmenttime()
    {
        return $this->belongsTo(Appointmenttime::class);
    }
}
