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
        'price',
        'time_period',
        'payment_date',
        'service_date',
    ];

    public function Scheduledetail()
    {
        return $this->hasOne(Scheduledetail::class);
    }
}
