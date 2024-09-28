<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    public function item()
    {
        return $this->hasMany(ServiceItem::class,'service_category_id');
    }
}
