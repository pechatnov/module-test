<?php

$APPLICATION->IncludeComponent(
    "pv:laptop.section",
    "",
    [
        "CODE" => $arResult["VARIABLES"]["MODEL"],
        "ENTITY_TYPE" => "LAPTOP",
        "SEF_FOLDER" => $arParams["SEF_FOLDER"],
        "HEADERS" => [
            "ID",
            "NAME",
            "YEAR",
            "PRICE"
        ]
    ]
);
