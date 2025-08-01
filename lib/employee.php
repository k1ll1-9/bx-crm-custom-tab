<?php

declare(strict_types=1);

namespace Test\Crmgrid;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity;
use Bitrix\Main\SystemException;

/**
 *
 */
class EmployeeTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return "test_crmgrid_employee_table";
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [new Entity\IntegerField(
            "ID",
            [
                "primary" => true,
                "autocomplete" => true,
            ]
        ),
            new Entity\BooleanField(
                'ACTIVE',
                [
                    "values" => ['N', 'Y']
                ]
            ),
            new Entity\IntegerField("SORT"),
            new Entity\StringField("NAME"),
            new Entity\DateTimeField("DATE_CREATE"),
        ];
    }

}
