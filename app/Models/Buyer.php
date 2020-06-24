<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Buyer
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
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereRegon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereTaxIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereZipCode($value)
 * @mixin \Eloquent
 */
class Buyer extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'city', 'zip_code', 'street', 'tax_id_number', 'regon', 'email', 'website', 'phone'
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

    public function getAddress(): array
    {
        $address = [];
        if ($this->street) {
            $address[] = $this->street;
        }
        if ($this->city || $this->zip_code) {
            $address[] = implode(' ', [$this->city, $this->zip_code]);
        }

        return $address;
    }

    public function getAddressString(): string
    {
        $address = $this->getAddress();

        return implode(', ', $address);
    }
}