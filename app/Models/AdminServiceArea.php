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
    protected $casts = [
        'status' => 'boolean'
    ];
    // AdminServiceArea 模型
    public function masterearea()
    {
        return $this->belongsToMany(MasterServiceArea::class, 'admin_master_area_relationship', 'admin_service_area_id', 'master_service_area_id');
    }

}
