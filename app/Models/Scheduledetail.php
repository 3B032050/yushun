<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheduledetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'schedulerecord_id',
        'status',
        'price',
    ];

    public function schedulerecord()
    {
        return $this->hasOne(Schedulerecord::class);
    }
}
