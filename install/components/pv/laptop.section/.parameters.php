<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "CODE" => [
            "NAME" => "Символьный код родителя",
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "GROUP" => "BASE"
        ],
        "SEF_FOLDER" => [
            "NAME" => "SEF_FOLDER",
            "GROUP" => "BASE"
        ],
        "ENTITY_TYPE" => [
            "NAME" => "Тип сущности",
            "TYPE" => "LIST",
            "VALUES" => [
                "VENDOR" => "Производители",
                "MODEL" => "Модели",
                "LAPTOP" => "Ноутбуки"
            ],
            "GROUP" => "BASE",
        ],
        "HEADERS" => [
            "NAME" => "Поля",
            "TYPE" => "LIST",
            "VALUES" => [
                "ID" => "ID",
                "NAME" => "Название",
                "YEAR" => "Год выпуска",
                "PRICE" => "Цена",
            ],
            "MULTIPLE" => "Y",
            "GROUP" => "BASE",
        ]
    ],
];