<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'service_area_id',
        'service_category_id',
    ];
    public function service_areas()
    {
        return $this->belongsToMany(AdminServiceArea::class, 'area_item_relationship', 'service_area_id', 'service_item_id');
    }
    public function masters()
    {
        return $this->belongsToMany(Master::class, 'item_master_relationship', 'master_id', 'service_item_id');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

}
