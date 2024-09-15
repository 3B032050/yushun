<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointmenttime extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'master_id',
        'serviceitem_id',
        'service_date',
        'time_period',
        'status',
    ];



}
