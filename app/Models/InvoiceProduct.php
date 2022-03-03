<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property float $amount
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice $invoice
 * @property-read \App\Models\User $user
 * @method static Builder|InvoiceProduct newModelQuery()
 * @method static Builder|InvoiceProduct newQuery()
 * @method static Builder|InvoiceProduct query()
 * @method static Builder|InvoiceProduct whereAmount($value)
 * @method static Builder|InvoiceProduct whereCreatedAt($value)
 * @method static Builder|InvoiceProduct whereId($value)
 * @method static Builder|InvoiceProduct whereInvoiceId($value)
 * @method static Builder|InvoiceProduct whereMeasureUnit($value)
 * @method static Builder|InvoiceProduct whereName($value)
 * @method static Builder|InvoiceProduct wherePrice($value)
 * @method static Builder|InvoiceProduct whereTaxPercent($value)
 * @method static Builder|InvoiceProduct whereUpdatedAt($value)
 * @method static Builder|InvoiceProduct create($value)
 * @method static Builder|InvoiceProduct make($value)
 * @mixin Model
 */
class InvoiceProduct extends Model
{
	/** @var string[] */
    protected $fillable = [
        'name', 'measure_unit', 'price', 'tax_percent', 'vat', 'amount',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

    public function priceWithVat(): float
    {
        return $this->price * $this->calculateVat();
    }

    public function isOwner(User $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user instanceof User) {
            throw new LogicException('User not logged!');
        }

        return $user->id === $this->user->id;
    }

    public function calculateVat(): float|int
    {
        $vat = 1;
        if (is_numeric($this->tax_percent)) {
            $vat = 1 + $this->tax_percent / 100;
        }

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
        $tax_percent = 0;
        if (is_numeric($this->tax_percent)) {
            $tax_percent = ($this->tax_percent / 100);
        }

        return $this->amount * $this->price * $tax_percent;
    }

    public function formattedPriceWithVat(): float
    {
        $price = $this->price;
        $vat = $this->calculateVat();
        if (is_numeric($vat)) {
            $price *= $vat;
        }
		return (float) $price;
    }
}