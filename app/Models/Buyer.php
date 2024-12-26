<?php

declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Database\Factories\BuyerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use LogicException;

/**
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
 * @method static Builder|Buyer findSimilarSlugs($attribute, $config, $slug)
 * @method static Builder|Buyer newModelQuery()
 * @method static Builder|Buyer newQuery()
 * @method static Builder|Buyer query()
 * @method static Builder|Buyer whereBankAccount($value)
 * @method static Builder|Buyer whereBankName($value)
 * @method static Builder|Buyer whereCity($value)
 * @method static Builder|Buyer whereCreatedAt($value)
 * @method static Builder|Buyer whereEmail($value)
 * @method static Builder|Buyer whereId($value)
 * @method static Builder|Buyer whereName($value)
 * @method static Builder|Buyer wherePhone($value)
 * @method static Builder|Buyer whereRegon($value)
 * @method static Builder|Buyer whereSlug($value)
 * @method static Builder|Buyer whereStreet($value)
 * @method static Builder|Buyer whereTaxIdNumber($value)
 * @method static Builder|Buyer whereUpdatedAt($value)
 * @method static Builder|Buyer whereUserId($value)
 * @method static Builder|Buyer whereWebsite($value)
 * @method static Builder|Buyer whereZipCode($value)
 */
class Buyer extends Model
{
    /** @use HasFactory<BuyerFactory> */
    use Sluggable, HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'name', 'city', 'zip_code', 'street', 'tax_id_number', 'regon', 'email', 'website', 'phone',
    ];

    public function isOwner(User|null $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (!$user instanceof User) {
            throw new LogicException('User not logged!');
        }

        return $user->id === $this->user->id;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array{slug: array{source: 'name'}}
     */
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

    public function getAddressString(): string
    {
        $address = $this->getAddress();

        return implode(', ', $address);
    }

    /**
     * @return string[]
     */
    public function getAddress(): array
    {
        $address = [];
        if ($this->street !== '') {
            $address[] = $this->street;
        }
        if ($this->city !== '' || $this->zip_code !== '') {
            $address[] = implode(' ', [$this->city, $this->zip_code]);
        }

        return $address;
    }
}
