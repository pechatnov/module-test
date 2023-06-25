<?php

namespace PV\Laptop;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;

class LaptopOptionTable extends \Bitrix\Main\ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'pv_laptop_option';
    }

    /**
     * @throws ArgumentException
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return array(
            (new IntegerField('LAPTOP_ID'))->configurePrimary(true),
            (new Reference(
                'LAPTOP',
                LaptopTable::class,
                Join::on('this.LAPTOP_ID', 'ref.ID')
            ))
                ->configureJoinType('inner'),

            (new IntegerField('OPTION_ID'))->configurePrimary(true),
            (new Reference(
                'OPTION',
                OptionTable::class,
                Join::on('this.OPTION_ID', 'ref.ID')
            ))
                ->configureJoinType('inner'),
            new StringField('VALUE', array()),
        );
    }
}
