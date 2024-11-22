<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // 確保引入此類
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Master extends Authenticatable // 修改這裡
{
    use Notifiable;
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'position',
    ];

    protected $hidden = ['password', 'remember_token'];
    public function isMaster()
    {
        return true;
    }

    public function rentuniforms()
    {
        return $this->hasMany(RentUniform::class, 'master_id');
    }

}
