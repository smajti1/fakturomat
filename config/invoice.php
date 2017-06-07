<?php

return [
    // use 3-letter currency code
    // ISO 4217 -> http://www.xe.com/symbols.php
    'currency' => 'PLN',

    'tax_rates' => [
        'PLN' => [
            [
                'id'      => '23',
                'label'   => '23%',
                'percent' => 23,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'id'      => '8',
                'label'   => '8%',
                'percent' => 8,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'id'      => '5',
                'label'   => '5%',
                'percent' => 5,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'id'      => '4',
                'label'   => '4%',
                'percent' => 4,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'id'      => '0',
                'label'   => '0%',
                'percent' => 0,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'id'      => 'zwolniony',
                'label'   => 'zwolniony',
                'percent' => 0,
                'from'    => '2011-01-01',
                'to'      => null,
            ],
            [
                'id'      => 'nie_podlega',
                'label'   => 'nie podlega',
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