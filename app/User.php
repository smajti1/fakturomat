<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['email', 'password',];
    protected $hidden = ['password', 'remember_token',];


    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function buyers()
    {
        return $this->hasMany(Buyer::class);
    }
}
