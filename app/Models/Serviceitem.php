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
    public function service_areas()
    {
        return $this->belongsToMany(Servicearea::class, 'area_item_relationship', 'service_area_id', 'service_item_id');
    }
    public function masters()
    {
        return $this->belongsToMany(Masters::class, 'item_master_relationship', 'master_id', 'service_item_id');
    }
}
