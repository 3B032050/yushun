<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'name',
        'quantity',
        'photo',
    ];

    public function borrowing_record()
    {
        return $this->hasMany(BorrowingRecord::class, 'equipment_id');
    }
}
