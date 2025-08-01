<?php
/**
 * @global CMain $APPLICATION
 */
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Test\Crmgrid\Grid;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (!check_bitrix_sessid()) {
    die();
}

$APPLICATION->ShowAjaxHead();

Asset::getInstance()->addJs('/local/modules/test.crmgrid/js/grid.js');
Loader::includeModule('test.crmgrid');
Loader::includeModule('crm');

$nav = $nav = Grid::getNav();
$rows = Grid::getRows(Grid::getFilterVars(), $nav);

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => Grid::GRID_ID,
        'GRID_ID' => Grid::GRID_ID,
        'FILTER' => Grid::getFilterBox(),
        'ENABLE_LABEL' => true,
    ]
);

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => Grid::GRID_ID,
        'COLUMNS' => Grid::getHeaders(),
        'ROWS' => $rows,
        'SHOW_ROW_CHECKBOXES' => false,
        'NAV_OBJECT' => $nav,
        'PAGE_SIZES' => [
            ['NAME' => '5', 'VALUE' => '5'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
        ],
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU' => true,
        'SHOW_GRID_SETTINGS_MENU' => true,
        'SHOW_NAVIGATION_PANEL' => true,
        'SHOW_PAGINATION' => true,
        'SHOW_SELECTED_COUNTER' => true,
        'SHOW_TOTAL_COUNTER' => true,
        'SHOW_PAGESIZE' => true,
        'SHOW_ACTION_PANEL' => true,
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'ALLOW_SORT' => true,
        'ALLOW_PIN_HEADER' => true,
    ]
);

\CMain::FinalActions();




