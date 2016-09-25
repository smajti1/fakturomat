<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{

    protected $fillable = [
        'name', 'address', 'nip', 'regon', 'email', 'www', 'phone', 'bank_account_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner(User $user = null):bool
    {
        $user = $user ?: \Auth::user();

        return $user->id !== $this->user->id;
    }
}