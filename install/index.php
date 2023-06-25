<?php

use Bitrix\Main\ArgumentException;
use Bitrix\Main\IO\InvalidPathException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class pv_laptop extends \CModule
{
    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';

        $this->MODULE_ID = "pv.laptop";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("LAPTOP_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("LAPTOP_DESCRIPTION");
        $this->MODULE_SORT = 0;
        $this->PARTNER_NAME = Loc::getMessage("LAPTOP_AUTHOR");
        $this->PARTNER_URI = "mailto: pechatnov.v@gmail.com";
        $this->MODULE_GROUP_RIGHTS = "Y";
    }

    /**
     * @throws LoaderException
     * @throws ArgumentException
     * @throws SystemException
     * @throws InvalidPathException
     */
    public function doInstall(): false
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        // проверяем, что версия ядра поддерживает D7
        if (CheckVersion(ModuleManager::getVersion("main"), "14.00.00")) {
            // шаг 1
            if ($request["step"] == 1 || !isset($request["step"])) {
                $APPLICATION->IncludeAdminFile(
                    sprintf(
                        '%s %s',
                        Loc::getMessage("LAPTOP_INSTALL_TITLE"),
                        Loc::getMessage("LAPTOP_NAME")
                    ),
                    __DIR__ . "/step1.php"
                );
            } elseif ($request["step"] == 2) {
                ModuleManager::registerModule($this->MODULE_ID);

                if ($request["delete-tables"] == "Y") {
                    $this->unInstallDB();
                }
                $this->installFiles();
                $this->installDB();

                $APPLICATION->IncludeAdminFile(
                    sprintf(
                        '%s %s',
                        Loc::getMessage("LAPTOP_INSTALL_TITLE"),
                        Loc::getMessage("LAPTOP_NAME")
                    ),
                    __DIR__ . "/step2.php"
                );
            }
        } else {
            $APPLICATION->ThrowException(
                Loc::getMessage("LAPTOP_INSTALL_ERROR_VERSION")
            );
        }

        return false;
    }

    /**
     * @throws LoaderException
     * @throws SystemException
     * @throws ArgumentException
     * @throws Exception
     */
    public function installDB(): void
    {
        Loader::includeModule($this->MODULE_ID);


        $tables = [
            [
                "connection" => Application::getConnection(PV\Laptop\VendorTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\VendorTable')->getDBTableName(),
                "instance" => Base::getInstance('PV\Laptop\VendorTable')
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\OptionTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\OptionTable')->getDBTableName(),
                "instance" => Base::getInstance('PV\Laptop\OptionTable')
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\LaptopTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\LaptopTable')->getDBTableName(),
                "instance" => Base::getInstance('PV\Laptop\LaptopTable')
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\ModelTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\ModelTable')->getDBTableName(),
                "instance" => Base::getInstance('PV\Laptop\ModelTable')
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\LaptopOptionTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\LaptopOptionTable')->getDBTableName(),
                "instance" => Base::getInstance('PV\Laptop\LaptopOptionTable')
            ],
        ];

        foreach ($tables as $table) {
            if (!$table["connection"]->isTableExists($table["name"])) {
                $table["instance"]->createDbTable();
            }
        }

        // заполнение тестовыми данными
        $testData = [];

        include(__DIR__ . "/testdata.php");

        $generateCode = function ($name) {
            return Cutil::translit($name, "ru", ["replace_space" => "-", "replace_other" => "-"]);
        };

        foreach ($testData["VENDORS"] as $item) {
            \PV\Laptop\VendorTable::add([
                "ID" => $item["ID"],
                "NAME" => $item["NAME"],
                "CODE" => $generateCode($item["NAME"])
            ]);
        }

        foreach ($testData["MODELS"] as $item) {
            \PV\Laptop\ModelTable::add([
                "ID" => $item["ID"],
                "NAME" => $item["NAME"],
                "CODE" => $generateCode($item["NAME"]),
                "VENDOR_ID" => $item["VENDOR_ID"]
            ]);
        }

        foreach ($testData["LAPTOP"] as $item) {
            \PV\Laptop\LaptopTable::add([
                "ID" => $item["ID"],
                "NAME" => $item["NAME"],
                "YEAR" => $item["YEAR"],
                "PRICE" => $item["PRICE"],
                "CODE" => $generateCode($item["NAME"]),
                "MODEL_ID" => $item["MODEL_ID"]
            ]);
        }

        foreach ($testData["OPTIONS"] as $item) {
            \PV\Laptop\OptionTable::add([
                "ID" => $item["ID"],
                "NAME" => $item["NAME"],
            ]);
        }

        foreach ($testData["LAPTOP_OPTIONS"] as $item) {
            foreach ($item["OPTIONS"] as $optionId => $value) {
                \PV\Laptop\LaptopOptionTable::add([
                    "LAPTOP_ID" => $item["LAPTOP_ID"],
                    "OPTION_ID" => $optionId,
                    "VALUE" => $value
                ]);
            }
        }
    }

    /**
     * @throws LoaderException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function doUninstall(): false
    {

        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        if ($request["step"] == 1 || !isset($request["step"])) {
            $APPLICATION->IncludeAdminFile(
                sprintf(
                    '%s %s',
                    Loc::getMessage("LAPTOP_INSTALL_TITLE"),
                    Loc::getMessage("LAPTOP_NAME")
                ),
                __DIR__ . "/unstep1.php"
            );
        } elseif ($request["step"] == 2) {
            if ($request["delete-tables"] == "Y") {
                $this->unInstallDB();
            }
            ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(
                sprintf(
                    '%s %s',
                    Loc::getMessage("LAPTOP_INSTALL_TITLE"),
                    Loc::getMessage("LAPTOP_NAME")
                ),
                __DIR__ . "/unstep2.php"
            );
        }

        return false;
    }

    /**
     * @throws InvalidPathException
     */
    public function InstallFiles(): void
    {
        $path = __DIR__ . "/components";

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path)) {
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"] . "/local/components", true, true);
        } else {
            throw new InvalidPathException($path);
        }
    }

    /**
     * @throws LoaderException
     * @throws ArgumentException
     * @throws SystemException
     */
    public function uninstallDB(): void
    {
        Loader::includeModule($this->MODULE_ID);

        $tables = [
            [
                "connection" => Application::getConnection(PV\Laptop\VendorTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\VendorTable')->getDBTableName()
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\OptionTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\OptionTable')->getDBTableName()
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\LaptopTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\LaptopTable')->getDBTableName()
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\ModelTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\ModelTable')->getDBTableName()
            ],
            [
                "connection" => Application::getConnection(PV\Laptop\LaptopOptionTable::getConnectionName()),
                "name" => Base::getInstance('PV\Laptop\LaptopOptionTable')->getDBTableName()
            ],
        ];

        foreach ($tables as $table) {
            if ($table["connection"]->isTableExists($table["name"])) {
                $table["connection"]->dropTable($table["name"]);
            }
        }
    }
}
