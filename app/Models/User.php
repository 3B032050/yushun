<?php

namespace App\Models;

 use App\Notifications\UserVerifyEmail;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'google_id',
        'master_id',
        'name',
        'password',
        'email',
        'phone',
        'mobile',
        'address',
        'line_id',
        'is_recurring',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserVerifyEmail);
    }

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function appointmenttime()
    {
        return $this->hasOne(AppointmentTime::class, 'user_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
