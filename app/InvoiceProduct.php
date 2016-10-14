<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{

    protected $fillable = [
        'name', 'measure_unit', 'price', 'vat', 'amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function priceWithVat()
    {
        return $this->price * $this->calculateVat();
    }

    public function isOwner(User $user = null):bool
    {
        $user = $user ?: \Auth::user();

        return $user->id === $this->user->id;
    }

    public function calculateVat()
    {
        $vat = 1 + $this->vat/100;

        return $vat;
    }
}