<?php

namespace PV\Laptop;

use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;


class OptionTable extends \Bitrix\Main\ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'pv_option';
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
            //Название
            new StringField('NAME', array(
                'required' => true,
            )),
        );
    }
}