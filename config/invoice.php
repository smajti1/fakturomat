<?php

return [
    // use 3-letter currency code
    // ISO 4217 -> http://www.xe.com/symbols.php
    'currency' => 'PLN',

    'tax_rates' => [
        'PLN' => [
            [
                'label'   => '23%',
                'percent' => 23,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'label'   => '8%',
                'percent' => 8,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'label'   => '5%',
                'percent' => 5,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'label'   => '4%',
                'percent' => 4,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'label'   => '0%',
                'percent' => 0,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'label'   => 'zw',
                'percent' => 0,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'label'   => 'np',
                'percent' => 0,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
        ],
    ],

    'measure_units' => [
        'pl' => [
            'op.'  => 'opakowanie',
            'szt.' => 'sztuka',
            'kg.'  => 'kilogram',
            'm'    => 'metr',
            'litr' => 'litr',
            'mb.'  => 'metr bieżący',
        ],
    ],
];