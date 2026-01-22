<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Authenticatable implements MustVerifyEmail, CanResetPasswordContract
{
    use HasFactory, Notifiable, HasRoles, CanResetPassword;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'avatar',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events()
    {
        return $this->hasMany(\App\Models\AAB_Event::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(\App\Models\AAB_Registration::class, 'user_id');
    }

    public function registeredEvents()
    {
        return $this->belongsToMany(\App\Models\AAB_Event::class, 'aab_registrations', 'user_id', 'event_id')
                    ->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}
