<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'address', 'tax_id_number', 'regon', 'email', 'www', 'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner(User $user = null):bool
    {
        $user = $user ?: \Auth::user();

        return $user->id === $this->user->id;
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}