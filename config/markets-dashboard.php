<?php

return [
    'name' => 'markets-dashboard',

    'routing' => [
        'prefix' => 'markets-dashboard',
        'as' => 'markets-dashboard.',
        'middleware' => [
            'web',
            'auth',
        ],
    ],
];
