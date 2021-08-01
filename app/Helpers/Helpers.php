<?php

function money_pl_format($number): string
{
    return number_format($number, 2, ',', ' ');
}

function spellOutAmount($price): string
{
    $numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::SPELLOUT);

    $price = number_format($price, 2, '.', '');
    [$integer, $decimal] = array_map('intval', explode('.', $price));

    $id = 'invoice.currency.' . config('invoice.currency');
    $price = $numberFormatter->format($integer) . ' ' . trans_choice("$id.integer", $integer);

    return $price . (' ' . $numberFormatter->format($decimal) . ' ' . trans_choice("$id.decimal", $decimal));
}

function activeTaxes(): array
{
    $taxes = config('invoice.tax_rates.' . config('invoice.currency'));
    $now = date('Y-m-d');
    $activeTaxes = [];

    foreach ($taxes as $tax) {
        if ($tax['from'] <= $now && (is_null($tax['to']) || $tax['to'] >= $now)) {
            $activeTaxes[$tax['id']] = $tax;
        }
    }

    return $activeTaxes;
}