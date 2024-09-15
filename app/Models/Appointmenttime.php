<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointmenttime extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'master_id',
        'serviceitem_id',
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
        return $this->hasMany(Schedulerecord::class,'appointmenttime_id');
    }

}
