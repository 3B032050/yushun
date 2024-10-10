<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MasterServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_service_area_id',
        'admin_service_item_id',
        'master_id',
    ];
//    public function master()
//    {
//        return $this->belongsTo(Master::class, 'master_id');
//    }
    // MasterServiceArea 模型
    public function adminarea()
    {
        return $this->belongsToMany(AdminServiceArea::class, 'admin_master_area_relationship', 'master_service_area_id', 'admin_service_area_id');
    }
    public function adminitem()
    {
        return $this->belongsTo(AdminServiceItem::class, 'admin_service_item_id');
    }
//    public function adminitem()
//    {
//        return $this->belongsToMany(AdminServiceItem::class, 'admin_master_area_relationship', 'master_service_area_id', 'admin_service_item_id');
//    }


}
