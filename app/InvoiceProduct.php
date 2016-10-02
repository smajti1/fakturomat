<?php

namespace App;

class InvoiceProduct extends Product
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
}