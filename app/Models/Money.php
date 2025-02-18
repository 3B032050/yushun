<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'master_id',
        'price',
        'payment_date',
    ];

    public function master()
    {
        return $this->hasOne(Master::class);
    }
}
