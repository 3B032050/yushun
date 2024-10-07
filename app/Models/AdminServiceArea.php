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
    public function master_service_area()
    {
        return $this->belongsToMany(ServiceItem::class, 'area_item_relationship', 'service_item_id', 'service_area_id');
    }

}
