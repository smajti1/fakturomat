<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CompanyInvoiceNumbers
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereAutoincrementNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereShowMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereShowNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereShowYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CompanyInvoiceNumbers whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyInvoiceNumbers extends Model
{
    protected $fillable = ['number', 'autoincrement_number', 'show_number', 'show_month', 'show_year'];
    protected $casts = [
        'autoincrement_number' => 'boolean',
        'show_number'          => 'boolean',
        'show_month'           => 'boolean',
        'show_year'            => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

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

    public function getFormattedNextNumber()
    {
        $number = $this->getPieces();
        if ($this->show_number && $this->autoincrement_number) {
            $number['number']++;
        }
        $number = implode('/', $number);

        return $number;
    }

}