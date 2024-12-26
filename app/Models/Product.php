<?php

declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use LogicException;

/**
 * @property int $id
 * @property string $name
 * @property string $measure_unit
 * @property float $price
 * @property string $tax_percent
 * @property string $slug
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static Builder|Product findSimilarSlugs($attribute, $config, $slug)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereMeasureUnit($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereTaxPercent($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereUserId($value)
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static Builder|Product withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 */
class Product extends Model
{
    use Sluggable, HasFactory;

    /** @var array<int, string> */
    protected $fillable = [
        'name', 'measure_unit', 'price', 'tax_percent',
    ];

    public function isOwner(User $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user instanceof User) {
            throw new LogicException('User not logged!');
        }

        return $user->id === $this->user->id;
    }

    /**
     * @return BelongsTo<User, Product>
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

    public function formattedPriceWithVat(): float
    {
        $price = $this->price;
        $vat = $this->calculateVat();
        if (is_numeric($vat)) {
            $price *= $vat;
        }
        return $price;
    }

    public function calculateVat(): string
    {
        $vat = $this->tax_percent;
        if (is_numeric($this->tax_percent)) {
            $vat = 1 + $this->tax_percent / 100;
        }

        return (string) $vat;
    }
}
