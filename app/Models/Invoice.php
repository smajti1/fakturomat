<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    const PAYMENT_CASH = 1;
    const PAYMENT_BANK_TRANSFER = 2;
    const STATUS_NOT_PAID = 1;
    const STATUS_PAID = 2;
    protected $fillable = ['payment', 'status', 'payment_at', 'number', 'issue_date', 'price', 'path'];
    protected $casts = [
        'price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if ($invoice->number == '') {
                $invoice->number = $invoice->company->companyInvoiceNumber->getFormattedNextNumber();
                $invoice->company->companyInvoiceNumber->increment('number');
            }
        });

    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice_products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function isOwner(User $user = null): bool
    {
        $user = $user ?: \Auth::user();

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
        $statusList = $this->statusList();
        $statusText = $statusList[$this->status];

        return $statusText;
    }

    public function statusList(): array
    {
        $statusList = [
            self::STATUS_NOT_PAID => 'nie zapłacona',
            self::STATUS_PAID     => 'zapłacona',
        ];

        return $statusList;
    }

    public function getPayment(): string
    {
        $paymentList = $this->getPaymentList();
        $paymentText = $paymentList[$this->payment];

        return $paymentText;
    }

    public function getPaymentList(): array
    {
        $paymentList = [
            self::PAYMENT_CASH          => 'gotówka',
            self::PAYMENT_BANK_TRANSFER => 'przelew',
        ];

        return $paymentList;
    }
}
