<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{

    protected $fillable = [
        'name', 'measure_unit', 'price', 'tax_percent', 'vat', 'amount',
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
        $vat = 1 + $this->tax_percent/100;

        return $vat;
    }

    public function grossPrice(): float
    {
        return $this->amount * $this->priceWithVat();
    }

    public function netPrice(): float
    {
        return $this->amount * $this->price;
    }

    public function taxAmount(): float
    {
        return $this->amount * $this->price * ($this->tax_percent / 100);
    }
}