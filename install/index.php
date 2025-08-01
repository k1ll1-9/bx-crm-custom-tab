<?php

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\EventManager;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Test\CrmGrid\EmployeeTable;

Loc::loadMessages(__FILE__);

require_once __DIR__ . '/../vendor/autoload.php';

/**
 *
 */
class test_crmgrid extends CModule
{

    /**
     *
     */
    function __construct()
    {
        $arModuleVersion = [];

        require(__DIR__ . "/version.php");

        $this->MODULE_ID = "test.crmgrid";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("TEST_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("TEST_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage("TEST_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("TEST_PARTNER_URI");
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = "N";
        $this->MODULE_GROUP_RIGHTS = "N";
    }

    /**
     * @return void
     */
    function DoInstall(): void
    {
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $this->installEvents();
        $this->installFiles();
        $this->addTestData();
        $APPLICATION->IncludeAdminFile(Loc::getMessage("TEST_INSTALL_TITLE"), __DIR__ . "/step.php");
    }

    /**
     * @return void
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws SqlQueryException
     * @throws LoaderException
     */
    function DoUninstall(): void
    {
        global $APPLICATION;

        $request = Context::getCurrent()->getRequest();

        if ((int)$request->getQuery('step') < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("TEST_INSTALL_TITLE"), __DIR__ . "/unstep1.php");
        } elseif ((int)$request->getQuery('step') === 2) {
            $this->unInstallEvents();

            if ($request->getQuery('save_data') != "Y") {
                $this->unInstallDB();
            }

            $this->unInstallFiles();

            ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(Loc::getMessage("TEST_INSTALL_TITLE"), __DIR__ . "/unstep2.php");
        }
    }


    /**
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    function installDB(): void
    {
        Loader::includeModule($this->MODULE_ID);

        if (!Application::getConnection(EmployeeTable::getConnectionName())->isTableExists(EmployeeTable::getTableName())) {
            EmployeeTable::getEntity()->createDbTable();
        }

    }

    /**
     * @return void
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws SqlQueryException
     * @throws LoaderException
     */
    function unInstallDB(): void
    {
        Loader::includeModule($this->MODULE_ID);

        Application::getConnection(EmployeeTable::getConnectionName())->queryExecute('DROP TABLE IF EXISTS ' . EmployeeTable::getTableName());

        Option::delete($this->MODULE_ID);
    }

    /**
     * @return void
     */
    function installEvents(): void
    {
        EventManager::getInstance()->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            'Test\Crmgrid\DealCard',
            'AddTab'
        );
    }

    /**
     * @return void
     */
    function unInstallEvents(): void
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            'Test\Crmgrid\DealCard',
            'AddTab'
        );
    }

    /**
     * @param int $count
     * @return void
     * @throws \Bitrix\Main\ObjectException
     */
    function addTestData(int $count = 50): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < $count; ++$i) {

            $dateTime = $faker->dateTimeBetween('2020-01-01', '2025-01-01')->format('Y-m-d H:i:s');

            EmployeeTable::add([
                'ACTIVE' => rand(0, 1) === 1 ? 'N' : 'Y',
                'SORT' => rand(0, $count) * 10,
                'NAME' => $faker->name(),
                'DATE_CREATE' => new DateTime($dateTime, 'Y-m-d H:i:s'),
            ]);

        }
    }
}
