<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use LogicException;

/**
 * @property int $id
 * @property int $payment
 * @property int $status
 * @property string $payment_at
 * @property string $number
 * @property string $issue_date
 * @property float $price
 * @property string $path
 * @property int $company_id
 * @property int $buyer_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Buyer $buyer
 * @property-read \App\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InvoiceProduct[] $invoice_products
 * @property-read int|null $invoice_products_count
 * @property-read \App\Models\User $user
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice whereBuyerId($value)
 * @method static Builder|Invoice whereCompanyId($value)
 * @method static Builder|Invoice whereCreatedAt($value)
 * @method static Builder|Invoice whereId($value)
 * @method static Builder|Invoice whereIssueDate($value)
 * @method static Builder|Invoice whereNumber($value)
 * @method static Builder|Invoice wherePath($value)
 * @method static Builder|Invoice wherePayment($value)
 * @method static Builder|Invoice wherePaymentAt($value)
 * @method static Builder|Invoice wherePrice($value)
 * @method static Builder|Invoice whereStatus($value)
 * @method static Builder|Invoice whereUpdatedAt($value)
 * @method static Builder|Invoice whereUserId($value)
 * @method static Builder|Invoice create($value)
 * @method static Builder|Invoice make($value)
 */
class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;

    public const int PAYMENT_CASH = 1;
    public const int PAYMENT_BANK_TRANSFER = 2;
    public const int STATUS_NOT_PAID = 1;
    public const int STATUS_PAID = 2;
    /** @var list<string> */
    protected $fillable = ['payment', 'status', 'payment_at', 'number', 'issue_date', 'price', 'path'];
    /** @var array<string, string> */
    protected $casts = [
        'price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(static function (self $invoice) {
            if ($invoice->number === '') {
                $invoice->number = $invoice->company->companyInvoiceNumber->getFormattedNextNumber();
                $invoice->company->companyInvoiceNumber->increment('number');
            }
        });

    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return HasMany<InvoiceProduct, $this>
     */
    public function invoice_products(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    /**
     * @return BelongsTo<Buyer, $this>
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

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

    public function grossSum(): int
    {
        $grossSum = 0;
        foreach ($this->invoice_products as $product) {
            $grossSum += $product->grossPrice();
        }

        return (int) $grossSum;
    }

    public function netSum(): int
    {
        $netSum = 0;
        foreach ($this->invoice_products as $product) {
            $netSum += $product->grossPrice();
        }

        return (int) $netSum;
    }

    /**
     * @return array<string, array<string, float>>
     */
    public function getTaxPercentsSum(): array
    {
        $taxPercents = [];
        foreach ($this->invoice_products as $product) {
            if (isset($taxPercents[$product->tax_percent])) {
                $taxPercents[$product->tax_percent]['netPrice'] += $product->netPrice();
                $taxPercents[$product->tax_percent]['grossPrice'] += $product->grossPrice();
                $taxPercents[$product->tax_percent]['amountVat'] += $product->taxAmount();
            } else {
                $taxPercents[$product->tax_percent] = [
                    'netPrice' => $product->netPrice(),
                    'grossPrice' => $product->grossPrice(),
                    'amountVat' => $product->taxAmount(),
                ];
            }
        }

        return $taxPercents;
    }

    /**
     * @return array{gross: int|float, net: int|float, tax: int|float}
     */
    public function getTotalSum(): array
    {
        $totalSum = [
            'gross' => 0,
            'net' => 0,
            'tax' => 0,
        ];
        foreach ($this->invoice_products as $product) {
            $totalSum['gross'] += $product->grossPrice();
            $totalSum['net'] += $product->netPrice();
            $totalSum['tax'] += $product->taxAmount();
        }

        return $totalSum;
    }

    public function getStatus(): string
    {
        return $this->statusList()[$this->status];
    }

    /**
     * @return array<int, string>
     */
    public function statusList(): array
    {
        return [
            self::STATUS_NOT_PAID => 'nie zapłacona',
            self::STATUS_PAID => 'zapłacona',
        ];
    }

    public function getPayment(): string
    {
        return $this->getPaymentList()[$this->payment];
    }

    /**
     * @return array<int, string>
     */
    public function getPaymentList(): array
    {
        return [
            self::PAYMENT_CASH => 'gotówka',
            self::PAYMENT_BANK_TRANSFER => 'przelew',
        ];
    }
}
