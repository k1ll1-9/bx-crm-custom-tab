<?php

namespace Test\Crmgrid;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity;

class EmployeeTable extends DataManager
{
    public static function getTableName()
    {
        return "test_crmgrid_employee_table";
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField(
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
