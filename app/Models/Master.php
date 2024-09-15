<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master extends Model
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

    public function User()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
