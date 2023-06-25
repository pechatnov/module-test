<?php

namespace PV\Laptop;

use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Result;
use Bitrix\Main\SystemException;

class VendorTable extends \Bitrix\Main\ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'pv_laptop_vendor';
    }

    /**
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return array(
            //ID
            new IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            //символьный код
            // TODO: контроль уникальности
            new StringField('CODE', array(
                'required' => true,
            )),
            //Название
            new StringField('NAME', array(
                'required' => true,
            )),
        );
    }

    /**
     * Returns selection by entity's code
     * @param string $code Entity's code
     * @return Result
     */
    public static function getByCode(string $code): Result
    {
        return static::getList(["filter" => [
            "CODE" => $code
        ]]);
    }
}
