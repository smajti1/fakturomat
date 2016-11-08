<?php

/*
 * Format $number to PL money format
 */
function money_pl_format($number): string
{
    return number_format($number, 2, ',', '&nbsp;');
}

/*
 * Spell out the amount
 */
function spellOutAmount($price): string
{
    $numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::SPELLOUT);

    $price = number_format($price, 2);
    list($integer, $decimal) = explode('.', $price);

    $price = $numberFormatter->format($integer) . ' ' . trans_choice('plurals.money.integer', $integer);
    $price .= ' ' . $numberFormatter->format($decimal) . ' ' . trans_choice('plurals.money.decimal', $decimal);

    return $price;
}

/*
 * filter and return active taxes
 */
function activeTaxes(): array
{
    $taxes = config('invoice.tax_rates.' . config('invoice.currency'));
    $now = date('Y-m-d');
    $activeTaxes = [];

    foreach ($taxes as $tax) {
        if ($tax['from'] <= $now && (is_null($tax['to']) || $tax['to'] >= $now)) {
            $activeTaxes[] = $tax;
        }
    }

    return $activeTaxes;
}