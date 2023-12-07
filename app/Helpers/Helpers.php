<?php

declare(strict_types=1);

function money_pl_format(float $number): string
{
    return number_format($number, 2, ',', ' ');
}

function spellOutAmount(float $price_to_format): string
{
    $numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::SPELLOUT);

    $price = number_format($price_to_format, 2, '.', '');
    [$integer, $decimal] = array_map('intval', explode('.', $price));

    $id = 'invoice.currency.' . config('invoice.currency');
    $price = $numberFormatter->format($integer) . ' ' . trans_choice("$id.integer", $integer);

    return $price . (' ' . $numberFormatter->format($decimal) . ' ' . trans_choice("$id.decimal", $decimal));
}

/**
 * @return array<string, array{id: string, label: string, percent: int, from: string, to: string|null}>
 */
function activeTaxes(): array
{
    /**
     * @var array<array{id: string, label: string, percent: int, from: string, to: string|null}> $taxes
     */
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
