<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MasterServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_service_area_id',
        'master_id',
    ];
    public function master()
    {
        return $this->belongsTo(Master::class);
    }
    public function adminServiceAreas()
    {
        return $this->belongsToMany(AdminServiceArea::class, 'admin_master_service_area');
    }
}
