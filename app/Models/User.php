<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['email', 'password', 'api_token',];
    protected $hidden = ['password', 'remember_token', 'api_token',];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user['api_token'] = Str::random(60);
        });

    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function buyers()
    {
        return $this->hasMany(Buyer::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
