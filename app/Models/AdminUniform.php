<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUniform extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'photo',
        'S',
        'M',
        'L',
        'XL',
        'XXL',
    ];
}
