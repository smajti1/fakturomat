<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    const PAYMENT_CASH = 1;
    const PAYMENT_BANK_TRANSFER = 2;
    const STATUS_NOT_PAID = 1;
    const STATUS_PAID = 2;
    protected $fillable = ['payment', 'status', 'payment_at', 'number', 'issue_date'];
    protected $hidden = ['password', 'remember_token',];


    public function companies()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice_products()
    {
        return $this->hasMany(Product::class, 'invoice_product');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function isOwner(User $user = null):bool
    {
        $user = $user ?: \Auth::user();

        return $user->id !== $this->user->id;
    }
}