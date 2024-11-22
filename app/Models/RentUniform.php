<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentUniform extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'master_id',
        'uniform_id',
        'size',
        'quantity',
        'status',
    ];

    public function master()
    {
        return $this->belongsTo(Master::class);
    }
    public function uniform()
    {
        return $this->belongsTo(AdminUniform::class);
    }
}
