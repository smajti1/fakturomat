<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * @property int $id
 * @property int $company_id
 * @property int $number
 * @property bool $autoincrement_number
 * @property bool $show_number
 * @property bool $show_month
 * @property bool $show_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @method static Builder|CompanyInvoiceNumbers newModelQuery()
 * @method static Builder|CompanyInvoiceNumbers newQuery()
 * @method static Builder|CompanyInvoiceNumbers query()
 * @method static Builder|CompanyInvoiceNumbers whereAutoincrementNumber($value)
 * @method static Builder|CompanyInvoiceNumbers whereCompanyId($value)
 * @method static Builder|CompanyInvoiceNumbers whereCreatedAt($value)
 * @method static Builder|CompanyInvoiceNumbers whereId($value)
 * @method static Builder|CompanyInvoiceNumbers whereNumber($value)
 * @method static Builder|CompanyInvoiceNumbers whereShowMonth($value)
 * @method static Builder|CompanyInvoiceNumbers whereShowNumber($value)
 * @method static Builder|CompanyInvoiceNumbers whereShowYear($value)
 * @method static Builder|CompanyInvoiceNumbers whereUpdatedAt($value)
 */
class CompanyInvoiceNumbers extends Model
{
    /** @var list<string> */
    protected $fillable = ['number', 'autoincrement_number', 'show_number', 'show_month', 'show_year'];
    /** @var array<string, string> */
    protected $casts = [
        'autoincrement_number' => 'boolean',
        'show_number' => 'boolean',
        'show_month' => 'boolean',
        'show_year' => 'boolean',
    ];

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getFormattedNextNumber(): string
    {
        $number = $this->getPieces();
        if ($this->show_number && $this->autoincrement_number) {
            isset($number['number']) ?: throw new LogicException('Cannot get next number!');
            $number['number']++;
        }

        return implode('/', $number);
    }

    /**
     * @return array{number? : int, month?: string, year?: string}
     */
    public function getPieces(): array
    {
        $number = [];

        if ($this->show_number) {
            $number['number'] = $this->number;
        }

        if ($this->show_month) {
            $number['month'] = date('m');
        }

        if ($this->show_year) {
            $number['year'] = date('Y');
        }

        return $number;
    }

}
