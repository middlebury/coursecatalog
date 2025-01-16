<?php

namespace App\Menu;

use App\Service\Osid\Runtime;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    /**
     * Add any other dependency you need...
     */
    public function __construct(
        private FactoryInterface $factory,
        private Runtime $osidRuntime,
    ) {
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('All Catalogs', ['route' => 'catalogs']);
        $subMenu = $menu['All Catalogs'];
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
        $catalogs = $lookupSession->getCourseCatalogs();
        while ($catalogs->hasNext()) {
            $catalog = $catalogs->getNextCourseCatalog();
            $subMenu->addChild(
                $catalog->getDisplayName(),
                [
                    'route' => 'view_catalog',
                    'routeParameters' => [
                        'catalogId' => $catalog->getId(),
                    ],
                ],
            );
        }

        $menu->addChild('Schedule Builder', ['route' => 'schedules']);

        $menu->addChild('Course Hub', ['uri' => 'https://courses.middlebury.edu']);
        $menu['Course Hub']->setLinkAttribute('class', 'link-external');

        $menu->addChild('Academic Calendar', ['uri' => 'https://go.middlebury.edu/academic+calendar']);
        $menu['Academic Calendar']->setLinkAttribute('class', 'link-external');

        $menu['Academic Calendar']->addChild('College/Schools Calendar', ['uri' => 'https://go.middlebury.edu/academic+calendar']);
        $menu['Academic Calendar']['College/Schools Calendar']->setLinkAttribute('class', 'link-external');

        $menu['Academic Calendar']->addChild('Institute Calendar', ['uri' => 'https://go.miis.edu/calendar']);
        $menu['Academic Calendar']['Institute Calendar']->setLinkAttribute('class', 'link-external');

        return $menu;
    }

    public function createSecondaryMenu(array $options): ItemInterface
    {
        if (!empty($options['selectedCatalogId']) && $options['selectedCatalogId'] instanceof \osid_id_Id) {
            $selectedCatalogId = $options['selectedCatalogId'];
        } else {
            $selectedCatalogId = null;
        }

        $menu = $this->factory->createItem('root');

        if ($selectedCatalogId) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
            $catalog = $lookupSession->getCourseCatalog($selectedCatalogId);
            $menu->addChild(
                $catalog->getDisplayName(),
                [
                    'route' => 'view_catalog',
                    'routeParameters' => [
                        'catalogId' => $catalog->getId(),
                    ],
                ],
            );
            $menu[$catalog->getDisplayName()]->addChild(
                'Search',
                [
                    'route' => 'search_offerings',
                    'routeParameters' => [
                        'catalogId' => $catalog->getId(),
                    ],
                ],
            );
        }

        $menu->addChild('Schedule Builder', ['route' => 'schedules']);

        return $menu;
    }
}
