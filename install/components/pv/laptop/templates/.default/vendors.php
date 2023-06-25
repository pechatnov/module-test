<?php

$APPLICATION->IncludeComponent(
    "pv:laptop.section",
    "",
    [
        "CODE" => "",
        "ENTITY_TYPE" => "VENDOR",
        "SEF_FOLDER" => $arParams["SEF_FOLDER"],
        "HEADERS" => [
            "ID",
            "NAME"
        ]
    ]
);
