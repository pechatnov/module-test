<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class LaptopSection extends CBitrixComponent
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

    /**
     * @throws Main\LoaderException
     */
    private function selectData(): void
    {
        $this->arResult = [];
        $this->arResult["ROWS"] = [];

        switch ($this->arParams["ENTITY_TYPE"]) {
            case "VENDOR":
                $entityClass = "PV\\Laptop\\VendorTable";
                break;
            case "MODEL":
                $entityClass = "PV\\Laptop\\ModelTable";
                $entityParentClass = "PV\\Laptop\\VendorTable";
                $filterField = "VENDOR_ID";
                break;
            case "LAPTOP":
                $entityClass = "PV\\Laptop\\LaptopTable";
                $entityParentClass = "PV\\Laptop\\ModelTable";
                $filterField = "MODEL_ID";
                break;
            default:
                throw new Main\LoaderException("Неверно указана сущность");
        }

        $grid_options = new Bitrix\Main\Grid\Options($this->arParams["ENTITY_TYPE"]);
        $sort = $grid_options->GetSorting(['sort' => ['ID' => 'ASC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $nav_params = $grid_options->GetNavParams();
        $nav = new Bitrix\Main\UI\PageNavigation($this->arParams["ENTITY_TYPE"]);
        $nav->allowAllRecords(true)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $this->arResult["NAV"] = $nav;
        $select = array_merge($this->arParams["HEADERS"], ["CODE"]);

        $params = [
            'select' => $select,
            'order' => $sort["sort"],
            'count_total' => true,
            'limit' => $nav->getLimit(),
            'offset' => $nav->getOffset(),
        ];

        if ($this->arParams["CODE"] != "") {
            $resultParent = $entityParentClass::getByCode($this->arParams["CODE"]);
            if ($parent = $resultParent->fetch()) {
                $this->arResult["PARENT"] = $parent;
            } else {
                return;
            }
            if (isset($filterField)) {
                $params["filter"] = [$filterField => $this->arResult["PARENT"]["ID"]];
            }
        }

        $result = $entityClass::getList($params);
        $this->arResult["TOTAL"] = $result->getCount();
        $nav->setRecordCount($this->arResult["TOTAL"]);


        $this->arResult["HEADERS"] = [];
        foreach ($this->arParams["HEADERS"] as $header) {
            $this->arResult["HEADERS"][] = [
                'id' => $header,
                'name' => $header,
                'sort' => $header,
                'default' => true
            ];
        }

        while ($row = $result->fetch()) {
            $row["LINK"] = match ($this->arParams["ENTITY_TYPE"]) {
                "VENDOR" => $this->arParams["SEF_FOLDER"] . $row["CODE"] . "/",
                "MODEL" => $this->arParams["SEF_FOLDER"] . $this->arParams["CODE"] . "/" . $row["CODE"] . "/",
                "LAPTOP" => $this->arParams["SEF_FOLDER"] . "detail/" . $row["CODE"] . "/",
                default => throw new Main\LoaderException("Неверно указана сущность"),
            };

            $row["NAME"] = "<a href=\"" . $row["LINK"] . "\">" . $row["NAME"] . "</a>";
            $this->arResult["ROWS"][] = [
                "data" => $row,
                'actions' => [
                    [
                        'text' => 'Открыть',
                        'onclick' => 'document.location.href="' . $row["LINK"] . '"'
                    ],
                ],
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
