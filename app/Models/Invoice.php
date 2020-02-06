<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{

    public const PAYMENT_CASH = 1;
    public const PAYMENT_BANK_TRANSFER = 2;
    public const STATUS_NOT_PAID = 1;
    public const STATUS_PAID = 2;
    protected $fillable = ['payment', 'status', 'payment_at', 'number', 'issue_date', 'price', 'path'];
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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice_products(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function isOwner(User $user = null): bool
    {
        $user = $user ?: Auth::user();

        return $user->id === $this->user->id;
    }

    public function grossSum(): int
    {
        $grossSum = 0;
        foreach ($this->invoice_products as $product) {
            $grossSum += $product->grossPrice();
        }

        return $grossSum;
    }

    public function netSum(): int
    {
        $netSum = 0;
        foreach ($this->invoice_products as $product) {
            $netSum += $product->grossPrice();
        }

        return $netSum;
    }

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
                    'netPrice'   => $product->netPrice(),
                    'grossPrice' => $product->grossPrice(),
                    'amountVat'  => $product->taxAmount(),
                ];
            }
        }

        return $taxPercents;
    }

    public function getTotalSum(): array
    {
        $totalSum = [
            'gross' => 0,
            'net'   => 0,
            'tax'   => 0,
        ];
        foreach ($this->invoice_products as $product) {
            $totalSum['gross'] += $product->grossPrice();
            $totalSum['net'] += $product->netPrice();
            if (is_numeric($product->taxAmount())) {
                $totalSum['tax'] += $product->taxAmount();
            }
        }

        return $totalSum;
    }

    public function getStatus(): string
    {
        return $this->statusList()[$this->status];
    }

    public function statusList(): array
    {
        return [
            self::STATUS_NOT_PAID => 'nie zapłacona',
            self::STATUS_PAID     => 'zapłacona',
        ];
    }

    public function getPayment(): string
    {
        return $this->getPaymentList()[$this->payment];
    }

    public function getPaymentList(): array
    {
        return [
            self::PAYMENT_CASH          => 'gotówka',
            self::PAYMENT_BANK_TRANSFER => 'przelew',
        ];
    }
}
