<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

        static::creating(static function ($user) {
            $user['api_token'] = Str::random(60);
        });

    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function buyers(): HasMany
    {
        return $this->hasMany(Buyer::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
