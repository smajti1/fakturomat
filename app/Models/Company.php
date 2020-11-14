<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string $zip_code
 * @property string $street
 * @property string $tax_id_number
 * @property string $regon
 * @property string $email
 * @property string $website
 * @property string $phone
 * @property string $bank_name
 * @property string $bank_account
 * @property string $slug
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CompanyInvoiceNumbers|null $companyInvoiceNumber
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereRegon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereTaxIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company withoutTrashed()
 * @mixin \Eloquent
 */
class Company extends Model
{
    use Sluggable, SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'city', 'zip_code', 'street', 'tax_id_number', 'regon', 'email', 'website', 'phone', 'bank_name', 'bank_account',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(static function (self $company) {
            $company->companyInvoiceNumber()->create([]);
        });

    }

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

    public function companyInvoiceNumber(): HasOne
    {
        return $this->hasOne(CompanyInvoiceNumbers::class);
    }
}
