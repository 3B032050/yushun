<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'major_area',
        'minor_area',
        'status',
    ];


}
