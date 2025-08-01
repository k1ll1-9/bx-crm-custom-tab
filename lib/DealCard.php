<?php

declare(strict_types=1);

namespace Test\Crmgrid;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

/**
 *
 */
class DealCard
{
    /**
     * @param Event $event
     * @return EventResult
     */
    public static function addTab(Event $event): EventResult
    {
        $tabs = $event->getParameter('tabs');
        $entityID = $event->getParameter('entityID');
        $entityTypeID = $event->getParameter('entityTypeID');

        if ($entityTypeID == \CCrmOwnerType::Deal) {
            $tabs[] = [
                'id' => Grid::GRID_ID,
                'name' => 'Сотрудники',
                'loader' => [
                    'serviceUrl' => '/local/modules/test.crmgrid/lib/ajax/grid.php?' . bitrix_sessid_get() . '&site=' . SITE_ID,
                    'componentData' => [
                        'template' => '',
                        'params' => [
                            'ENTITY_ID' => $entityID,
                            'ENTITY_TYPE' => $entityTypeID,
                            'TAB_ID' => Grid::GRID_ID,
                        ]
                    ],
                ]
            ];
        }

        return new EventResult(EventResult::SUCCESS, [
            'tabs' => $tabs,
        ]);
    }
}
