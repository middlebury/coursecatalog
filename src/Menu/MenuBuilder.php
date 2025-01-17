<?php

namespace App\Menu;

use App\Service\Osid\Runtime;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;

class MenuBuilder
{
    /**
     * Add any other dependency you need...
     */
    public function __construct(
        private FactoryInterface $factory,
        private Runtime $osidRuntime,
        private Security $security,
    ) {
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('All Catalogs', ['route' => 'catalogs']);
        $subMenu = $menu['All Catalogs'];
        $subMenu->setExtra('menuId', 1);
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
        $subMenu = $menu['Academic Calendar'];
        $subMenu->setExtra('menuId', 2);
        $subMenu->setLinkAttribute('class', 'link-external');

        $subMenu->addChild('College/Schools Calendar', ['uri' => 'https://go.middlebury.edu/academic+calendar']);
        $subMenu['College/Schools Calendar']->setLinkAttribute('class', 'link-external');

        $subMenu->addChild('Institute Calendar', ['uri' => 'https://go.miis.edu/calendar']);
        $subMenu['Institute Calendar']->setLinkAttribute('class', 'link-external');

        // Admin routes.
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Admin', ['route' => 'admin_index']);
            $subMenu = $menu['Admin'];
            $subMenu->setExtra('menuId', 3);
            $subMenu->addChild('Manage Term Visibility', ['route' => 'admin_terms_list']);
            $subMenu->addChild('Manage Anti-Requisites', ['route' => 'list_antirequisites']);
            $subMenu->addChild('Manage Catalog Archive Configurations', ['route' => 'export_config_form']);
            $subMenu->addChild('Manage Catalog Archive Scheduling', ['route' => 'export_list_jobs']);
            $subMenu->addChild('Masquerade', ['route' => 'masquerade']);
            $subMenu->addChild('View Catalog Markup Example', ['route' => 'markup']);
        }

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

        // Schedule link.
        $menu->addChild('Schedule Builder', ['route' => 'schedules']);

        return $menu;
    }

    public function createAdminMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');



        return $menu;
    }

}
