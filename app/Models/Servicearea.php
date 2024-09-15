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
    public function service_items()
    {
        return $this->belongsToMany(Serviceitem::class, 'area_item_relationship', 'service_item_id', 'service_area_id');
    }

}
