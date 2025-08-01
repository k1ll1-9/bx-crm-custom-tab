/**
 * Меняем URL запроса на бэк на корректный
 */
BX.addCustomEvent('Grid::beforeRequest', (gridObj, configObj) => {
        if (configObj.gridId === 'CRM_DEAL_CUSTOM_GRID' && configObj.url !== '/local/modules/test.crmgrid/lib/ajax/grid.php') {
            const grid = BX.Main.gridManager.getById('CRM_DEAL_CUSTOM_GRID')
            grid.instance.baseUrl = '/local/modules/test.crmgrid/lib/ajax/grid.php'
        }
    }
);
