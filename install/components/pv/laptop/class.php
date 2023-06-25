<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class Laptop extends CBitrixComponent
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
    public function executeComponent(): void
    {
        $this->includeComponentLang('class.php');
        $this->checkModules();

        if ($this->arParams['SEF_MODE'] != 'Y') {
            throw new Main\LoaderException("Только в режиме ЧПУ");
        }

        $arVariables = $arComponentVariables = $arVariableAliases = [];

        // определим страницу компонента по шаблону
        $componentPage = CComponentEngine::ParseComponentPath(
            $this->arParams['SEF_FOLDER'],
            $this->arParams['SEF_URL_TEMPLATES'],
            $arVariables
        );

        // если не определено и урл совпадает с базовой папкой
        if ($componentPage === false) {
            $requestURL = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPageDirectory();
            if ($requestURL == $this->arParams['SEF_FOLDER']) {
                $componentPage = 'vendors';
            }
        }

        CComponentEngine::initComponentVariables(
            $componentPage,
            $arComponentVariables,
            $arVariableAliases,
            $arVariables
        );

        $this->arResult = array(
            "FOLDER" => $this->arParams["SEF_FOLDER"],
            "VARIABLES" => $arVariables,
        );
        $this->includeComponentTemplate($componentPage);
    }
}
