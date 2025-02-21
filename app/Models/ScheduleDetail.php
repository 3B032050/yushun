<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'schedule_record_id',
        'before_photo',
        'after_photo',
        'score',
        'comment',
    ];

    public function schedulerecord()
    {
        return $this->hasOne(ScheduleRecord::class);
    }
}
