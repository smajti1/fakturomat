<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'measure_unit', 'price', 'tax_percent', 'imported_from_id', 'imported_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner(User $user = null): bool
    {
        $user = $user ?: \Auth::user();

        return $user->id === $this->user->id;
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function calculateVat(): string
    {
        $vat = $this->tax_percent;
        if (is_numeric($this->tax_percent)) {
            $vat = 1 + $this->tax_percent / 100;
        }

        return (string)$vat;
    }
}