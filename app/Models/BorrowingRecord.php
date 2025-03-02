<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'master_id',
        'equipment_id',
        'appointment_time_id',
        'status',
        'quantity',
        'borrowing_date',
        'return_date',
    ];


    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
    public function master()
    {
        return $this->belongsTo(Master::class, 'master_id');
    }

    public function appointmenttime()
    {
        return $this->belongsTo(AppointmentTime::class, 'appointment_time_id');
    }
}
