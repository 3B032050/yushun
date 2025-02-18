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
        'status',
        'quantity',
        'borrowing_date',
        'return_date',
    ];


    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
}
