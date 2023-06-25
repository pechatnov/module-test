<?php

$APPLICATION->IncludeComponent(
    "pv:laptop.section",
    "",
    [
        "CODE" => $arResult["VARIABLES"]["BRAND"],
        "ENTITY_TYPE" => "MODEL",
        "SEF_FOLDER" => $arParams["SEF_FOLDER"],
        "HEADERS" => [
            "ID",
            "NAME"
        ]
    ]
);
