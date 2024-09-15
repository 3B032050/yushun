<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serviceitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'service_item',
        'service_item_description',
        'service_area_id',
    ];
}
