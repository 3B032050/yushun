<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'position',
        'service_item_id',
        'service_area_id'
    ];

    public function serviceitem()
    {
        return $this->belongsToMany(Serviceitem::class, 'item_master_relationship', 'service_item_id', 'master_id');
    }
}
