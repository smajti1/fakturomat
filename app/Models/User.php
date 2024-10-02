<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use LogicException;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $api_token
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection&Buyer[] $buyers
 * @property-read int|null $buyers_count
 * @property-read Company|null $company
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereApiToken($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method User associate($value)
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 */
class User extends Authenticatable
{
    use Notifiable, HasFactory;

    /** @var array<int, string> */
    protected $fillable = ['email', 'password', 'api_token',];
    /** @var array<int, string> */
    protected $hidden = ['password', 'remember_token', 'api_token',];

    protected static function boot()
    {
        parent::boot();

        static::creating(static function ($user) {
            $user['api_token'] = Str::random(60);
        });

    }

    /**
     * @return HasOne<Company>
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    /**
     * @return HasMany<Product>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return HasMany<Buyer>
     */
    public function buyers(): HasMany
    {
        return $this->hasMany(Buyer::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function requireCompany(): Company
    {
        return $this->company ?: throw new LogicException('User without company!');
    }
}
