<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
    ];
    public function masterearea()
    {
        return $this->belongsToMany(MasterServiceArea::class, 'admin_master_area_relationship', 'admin_service_item_id', 'master_service_area_id');
    }

}
