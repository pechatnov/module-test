<?php

$APPLICATION->IncludeComponent(
    "pv:laptop.detail",
    "",
    [
        "CODE" => $arResult["VARIABLES"]["LAPTOP"],
        "SEF_FOLDER" => $arParams["SEF_FOLDER"],
    ]
);
