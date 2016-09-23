<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable = [
        'name', 'street', 'city', 'post_code', 'nip', 'regon', 'email', 'www', 'phone', 'bank_name',
        'bank_account_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}