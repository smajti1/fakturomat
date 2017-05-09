<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use Sluggable, SoftDeletes;

    protected $fillable = [
        'name', 'city', 'zip_code', 'street', 'tax_id_number', 'regon', 'email', 'website', 'phone', 'bank_name', 'bank_account',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($company) {
            $company->companyInvoiceNumber()->create([]);
        });

    }

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

    public function getAddress(): array
    {
        $address = [];
        if ($street = $this->street) {
            if (
                strpos($street, 'ul.') === false &&
                strpos($street, 'Aleje') !== false
            ) {
                $street = 'ul. ' . $street;
            }
            $address[] = $street;
        }
        if ($this->city || $this->zip_code) {
            $address[] = implode(' ', [$this->zip_code, $this->city]);
        }

        return $address;
    }

    public function companyInvoiceNumber()
    {
        return $this->hasOne(CompanyInvoiceNumbers::class);
    }
}