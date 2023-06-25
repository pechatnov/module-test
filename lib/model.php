<?php

namespace PV\Laptop;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;

class ModelTable extends \Bitrix\Main\ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'pv_laptop_model';
    }

    /**
     * @throws ArgumentException
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
            //Символьный код
            // TODO: контроль уникальности
            new StringField('CODE', array(
                'required' => true,
            )),
            //Название
            new StringField('NAME', array(
                'required' => true,
            )),
            // Производитель
            new IntegerField('VENDOR_ID', array(
                'required' => true,
            )),
            (new Reference(
                'VENDOR',
                VendorTable::class,
                Join::on('this.VENDOR_ID', 'ref.ID')
            ))
                ->configureJoinType('inner')
        );
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function getByCode($code): \Bitrix\Main\ORM\Query\Result
    {
        return static::getList(["filter" => [
            "CODE" => $code
        ]]);
    }

}
