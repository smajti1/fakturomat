<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'measure_unit', 'price', 'tax_percent'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner(User $user = null): bool
    {
        $user = $user ?: Auth::user();

        return $user->id === $this->user->id;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function getRouteKeyName(): string
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

    public function formattedPriceWithVat(): float
    {
        $price = $this->price;
        $vat = $this->calculateVat();
        if (is_numeric($vat)) {
            $price *= $vat;
        }
        return $price;
    }
}