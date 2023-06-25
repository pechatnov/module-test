<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class LaptopDetail extends CBitrixComponent
{
    /**
     * @throws Main\LoaderException
     */
    private function checkModules(): void
    {
        if (!Main\Loader::includeModule('pv.laptop')) {
            throw new Main\LoaderException(Loc::getMessage('LAPTOP_MODULE_NOT_INSTALLED'));
        }
    }

    private function selectData(): void
    {
        $this->arResult = [];

        $result = PV\Laptop\LaptopTable::getByCode($this->arParams["CODE"]);
        if ($data = $result->fetch()) {
            $this->arResult = $data;
        }

        $params = [
            'select' => array('VALUE', "OPTION.NAME"),
            'order' => array('OPTION.NAME' => 'ASC'),
            'filter' => ["LAPTOP_ID" => $this->arResult["ID"]]
        ];
        $resultOptions = PV\Laptop\LaptopOptionTable::getList(
            $params
        );
        $this->arResult["OPTIONS"] = [];
        while ($row = $resultOptions->fetch()) {
            $this->arResult["OPTIONS"][] = [
                "NAME" => $row["LAPTOP_OPTION_OPTION_NAME"],
                "VALUE" => $row["VALUE"]
            ];
        }
    }

    /**
     * @throws Main\LoaderException
     */
    public function executeComponent(): void
    {
        $this->includeComponentLang('class.php');
        $this->checkModules();
        $this->selectData();
        $this->includeComponentTemplate();
    }
}
