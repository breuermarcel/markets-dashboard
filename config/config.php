<?php

return [
    "name" => "finance-dashboard",

    "routing" => [
        "prefix" => "finance-dashboard",
        "middleware" => [
            "web"
        ]
    ],

    "tables" => [
        "stocks" => "bm_stocks",
    ]
];
