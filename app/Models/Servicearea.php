<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicearea extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'area',
        'status',
    ];


}
