<?php

namespace Test\Crmgrid;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Bitrix\Main\UI\PageNavigation;

class Grid
{
    const string GRID_ID = 'CRM_DEAL_CUSTOM_GRID';

    public static function getHeaders(): array
    {

        return [
            [
                'id' => 'ID',
                'name' => Loc::getMessage('TEST_GRID_HEADER_ID'),
                'sort' => 'ID',
                'first_order' => 'desc',
                'type' => 'int',
                'default' => true,
            ],
            [
                'id' => 'ACTIVE',
                'name' => Loc::getMessage('TEST_GRID_HEADER_ACTIVE'),
                'sort' => 'ACTIVE',
                'type' => 'text',
                'default' => true,
            ],
            [
                'id' => 'NAME',
                'name' => Loc::getMessage('TEST_GRID_HEADER_NAME'),
                'sort' => 'NAME',
                'default' => true,
                'type' => 'text',
            ],
            [
                'id' => 'DATE_CREATE',
                'name' => Loc::getMessage('TEST_GRID_HEADER_DATE_CREATE'),
                'sort' => 'DATE_CREATE',
                'type' => 'date',
                'default' => true,
            ],
            [
                'id' => 'SORT',
                'name' => Loc::getMessage('TEST_GRID_HEADER_SORT'),
                'sort' => 'SORT',
                'type' => 'int',
                'default' => true,
            ],
        ];
    }

    public static function getRows(array $filter,PageNavigation $nav): array
    {
        $employeesRes = EmployeeTable::getList([
                'select' => ['*'],
                'order' => self::getSort(),
                'filter' => $filter,
                'limit' => $nav->getLimit(),
                'offset' => $nav->getOffset(),
                'count_total' => true,
            ]
        );

        $nav->setRecordCount($employeesRes->getCount());

        $employees = $employeesRes->fetchAll();
        $rows = [];

        foreach ($employees as $employee) {
            $rows[] = [
                'id' => $employee['ID'],
                'columns' => $employee
            ];
        }

        return $rows;

    }

    public static function getSort(): array
    {
        $grid = new GridOptions(self::GRID_ID);
        $sort = $grid->getSorting()['sort'];

        return $sort ?: ['ID' => 'asc'];
    }

    public static function getFilterBox(): array
    {
        return [
            [
                'id' => 'ID',
                'name' => Loc::getMessage('TEST_GRID_HEADER_ID'),
                'type' => 'number',
                'default' => true,
            ],
            [
                'id' => 'NAME',
                'name' => Loc::getMessage('TEST_GRID_HEADER_NAME'),
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'ACTIVE',
                'name' => Loc::getMessage('TEST_GRID_HEADER_ACTIVE'),
                'type' => 'checkbox',
                'default' => true,
            ],
            [
                'id' => 'DATE_CREATE',
                'name' => Loc::getMessage('TEST_GRID_HEADER_DATE_CREATE'),
                'type' => 'date',
                'default' => true,
            ],
            [
                'id' => 'SORT',
                'name' => Loc::getMessage('TEST_GRID_HEADER_SORT'),
                'type' => 'number',
                'default' => true,
            ],
        ];
    }

    public static function getFilterVars() :array
    {
        $filterOption = new FilterOptions(self::GRID_ID);
        $filterData = $filterOption->getFilter([]);
        $filter = [];

        if (isset($filterData['NAME'])) {
            $filter['NAME'] = "%" . $filterData['NAME'] . "%";
        }
        if (isset($filterData['ACTIVE'])) {
            $filter['ACTIVE'] = $filterData['ACTIVE'];
        }
        if (isset($filterData['ID_from']) && $filterData['ID_from'] !== ''){
            $filter['>=ID'] = $filterData['ID_from'];
        }

        if (isset($filterData['ID_to']) && $filterData['ID_to'] !== '') {
            $filter['<=ID'] = $filterData['ID_to'];

        }
        if (isset($filterData['SORT_from']) && $filterData['SORT_from'] !== ''){
            $filter['>=SORT'] = $filterData['SORT_from'];
        }
        if (isset($filterData['SORT_to']) && $filterData['SORT_to'] !== '') {
            $filter['<=SORT'] = $filterData['SORT_to'];

        }
        if (isset($filterData['DATE_CREATE_from']) || isset($filterData['DATE_CREATE_to'])) {
            $filter['>=DATE_CREATE'] = $filterData['DATE_CREATE_from'];
            $filter['<=DATE_CREATE'] = $filterData['DATE_CREATE_to'];
        }

        return $filter;
    }


    public static function getNav(): PageNavigation
    {
        $gridOptions = new GridOptions(Grid::GRID_ID);
        $navParams = $gridOptions->GetNavParams();
        $nav = new PageNavigation(Grid::GRID_ID);
        $nav->allowAllRecords(true)
            ->setPageSize($navParams['nPageSize'])
            ->initFromUri();

        return $nav;
    }
}
