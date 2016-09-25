<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'name', 'pkwiu', 'measure_unit', 'price', 'vat',
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