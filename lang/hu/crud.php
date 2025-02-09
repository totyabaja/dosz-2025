<?php

return [
    'users' => [
        'filament' => [
            'name' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'email' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'password' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'admin' => [
                'helper_text' => '',
                'label' => '',
            ],
        ],
        'itemTitle' => 'Felhasználó',
        'collectionTitle' => 'Felhasználók',
        'inputs' => [
            'name' => [
                'label' => 'Név',
                'placeholder' => 'Név',
            ],
            'email' => [
                'label' => 'Email',
                'placeholder' => 'Email',
            ],
            'password' => [
                'label' => 'Password',
                'placeholder' => 'Password',
            ],
            'admin' => [
                'label' => 'Admin',
                'placeholder' => 'Admin',
            ],
        ],
    ],
    'counties' => [
        'itemTitle' => 'Megye',
        'collectionTitle' => 'Megyék',
        'inputs' => [
            'name' => [
                'label' => 'Név',
                'placeholder' => 'Név',
            ],
            'code' => [
                'label' => 'Code',
                'placeholder' => 'Code',
            ],
        ],
        'filament' => [
            'name' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'code' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
        ],
    ],
    'countyUsers' => [
        'inputs' => [
            'county_id' => [
                'label' => 'Megye ID',
                'placeholder' => 'Megye ID',
            ],
        ],
        'filament' => [
            'county_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
        ],
    ],
    'timePeriods' => [
        'itemTitle' => 'Időperiódus',
        'collectionTitle' => 'Időperiódusok',
        'inputs' => [
            'year' => [
                'label' => 'Év',
                'placeholder' => 'Év',
            ],
            'timepoint' => [
                'label' => 'Időpont',
                'placeholder' => 'Időpont',
            ],
        ],
        'filament' => [
            'year' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'timepoint' => [
                'helper_text' => '',
                'label' => '',
            ],
        ],
    ],
    'measurementPoints' => [
        'itemTitle' => 'Mérési pont',
        'collectionTitle' => 'Mérési pontok',
        'inputs' => [
            'year' => [
                'label' => 'Év',
                'placeholder' => 'Év',
            ],
            'megye_id' => [
                'label' => 'Megye id',
                'placeholder' => 'Megye id',
            ],
            'county_id' => [
                'label' => 'Megye ID',
                'placeholder' => 'Megye ID',
            ],
            'code' => [
                'label' => 'Code',
                'placeholder' => 'Code',
            ],
            'stand' => [
                'label' => 'Stand',
                'placeholder' => 'Stand',
            ],
            'telepules' => [
                'label' => 'Telepules',
                'placeholder' => 'Telepules',
            ],
            'stand_nev' => [
                'label' => 'Stand név',
                'placeholder' => 'Stand név',
            ],
            'eovx' => [
                'label' => 'Eovx',
                'placeholder' => 'Eovx',
            ],
            'eovy' => [
                'label' => 'Eovy',
                'placeholder' => 'Eovy',
            ],
            'wgsx' => [
                'label' => 'Wgsx',
                'placeholder' => 'Wgsx',
            ],
            'wgsy' => [
                'label' => 'Wgsy',
                'placeholder' => 'Wgsy',
            ],
        ],
        'filament' => [
            'year' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'megye_id' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'county_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
            'code' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'stand' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'telepules' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'stand_nev' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'eovx' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'eovy' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'wgsx' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'wgsy' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
        ],
    ],
];
