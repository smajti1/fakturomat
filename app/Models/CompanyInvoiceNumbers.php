<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInvoiceNumbers extends Model
{
    protected $fillable = ['number', 'autoincrement_number', 'show_number', 'show_month', 'show_year'];
    protected $casts = [
        'autoincrement_number' => 'boolean',
        'show_number'          => 'boolean',
        'show_month'           => 'boolean',
        'show_year'            => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getPieces()
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