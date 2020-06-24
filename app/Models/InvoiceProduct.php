<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\InvoiceProduct
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereMeasureUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereTaxPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\InvoiceProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvoiceProduct extends Model
{

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

    public function priceWithVat()
    {
        return $this->price * $this->calculateVat();
    }

    public function isOwner(User $user = null): bool
    {
        $user = $user ?: Auth::user();

        return $user->id === $this->user->id;
    }

    public function calculateVat()
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
        return $price;
    }
}