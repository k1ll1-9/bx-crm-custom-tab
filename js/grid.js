BX.addCustomEvent('Grid::beforeRequest', (gridObj, configObj) => {
        if (configObj.gridId === 'CRM_DEAL_CUSTOM_GRID' && configObj.url !== '/local/modules/test.crmgrid/lib/ajax/grid.php')
            configObj.url = '/local/modules/test.crmgrid/lib/ajax/grid.php';
    }
);
