<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = [
    "PARAMETERS" => [
        'SEF_MODE' => [
            'vendors' => [
                'NAME' => 'Список производителей',
                'DEFAULT' => '',
            ],
            'vendor' => [
                'NAME' => 'Список моделей производителя',
                'DEFAULT' => '#BRAND#/',
                "VARIABLES" => [
                    "BRAND"
                ],
            ],
            'model' => [
                'NAME' => 'Список моделей ноутбуков',
                'DEFAULT' => '#BRAND#/#MODEL#/',
                "VARIABLES" => [
                    "BRAND",
                    "MODEL"
                ],
            ],
            'detail' => [
                'NAME' => 'Страница ноутбука',
                'DEFAULT' => 'detail/#LAPTOP#/',
                "VARIABLES" => [
                    "LAPTOP"
                ],
            ],
        ],
    ],
];