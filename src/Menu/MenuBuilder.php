<?php

namespace App\Menu;

use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuBuilder
{
    /**
     * Add any other dependency you need...
     */
    public function __construct(
        private FactoryInterface $factory,
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private Security $security,
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator,
        private array $catalogMenuLinks = [],
    ) {
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $user = $this->security->getUser();

        // Note the current route so that we can mark the Search page for a
        // catalog as active when looking at catalog-specific pages that aren't
        // in a menu.
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest) {
            $currentRoute = $currentRequest->get('_route');
            $currentRouteIsInMenu = in_array($currentRoute, ['view_catalog', 'search_offerings', 'schedules']);
        } else {
            $currentRouteIsInMenu = false;
        }
        if (!empty($options['selectedCatalogId']) && $options['selectedCatalogId'] instanceof \osid_id_Id) {
            $selectedCatalogId = $options['selectedCatalogId'];
        } else {
            $selectedCatalogId = null;
        }

        $menu = $this->factory->createItem('Course Catalog', ['route' => 'home']);

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
            $subMenu[$catalog->getDisplayName()]->addChild(
                'Search',
                [
                    'route' => 'search_offerings',
                    'routeParameters' => [
                        'catalogId' => $catalog->getId(),
                    ],
                ],
            );
            // Add any manually configured links for this catalog.
            $catalogIdString = $this->osidIdMap->toString($catalog->getId());
            if (!empty($this->catalogMenuLinks[$catalogIdString])) {
                foreach ($this->catalogMenuLinks[$catalogIdString] as $link) {
                    $subMenu[$catalog->getDisplayName()]->addChild($link['label'], ['uri' => $link['uri']]);
                    $subMenu[$catalog->getDisplayName()][$link['label']]->setLinkAttribute('class', 'link-external');
                }
            }
            // If we are on a catalog-specific page that is not in the menu
            // (like a course page), mark the search page as the currently
            // active item so that breadcrumbs can point at the catalog.
            if ($selectedCatalogId && $selectedCatalogId->isEqual($catalog->getId()) && !$currentRouteIsInMenu) {
                $subMenu[$catalog->getDisplayName()]['Search']->setCurrent(true);
            }
        }

        if ($user) {
            $menu->addChild('Schedule Builder', ['route' => 'schedules']);
        } else {
            $menu->addChild('Schedule Builder', [
                'route' => 'login',
                'routeParameters' => [
                    'returnTo' => $this->urlGenerator->generate('schedules', ['catalogId' => $selectedCatalogId]),
                ],
            ]);
        }

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
            $menu->addChild('Administration', ['route' => 'admin_index']);
            $subMenu = $menu['Administration'];
            $subMenu->setExtra('menuId', 3);
            $subMenu->addChild('Manage Term Visibility', ['route' => 'admin_terms_list']);
            $subMenu->addChild('Manage Anti-Requisites', ['route' => 'list_antirequisites']);
            $subMenu->addChild('Manage Catalog Archive Configurations', ['route' => 'export_config_form']);
            $subMenu->addChild('Manage Catalog Archive Scheduling', ['route' => 'export_list_jobs']);
            $subMenu->addChild('Masquerade', ['route' => 'masquerade']);
            $subMenu->addChild('View Catalog Markup Example', ['route' => 'markup']);
        }

        // Log in / Log out.
        if ($user) {
            $menu->addChild('Log out', ['route' => 'saml_logout']);
        } else {
            $menu->addChild('Log in', [
                'route' => 'login',
                'routeParameters' => [
                    'returnTo' => $currentRequest->getRequestUri(),
                ],
            ]);
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

        $menu = $this->factory->createItem('All Catalogs', ['route' => 'list_catalogs']);
        $menu->setLabel('In this Catalog');

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

            $currentRequest = $this->requestStack->getCurrentRequest();
            $currentRoute = $currentRequest->get('_route');
            $routeParameters = ['catalogId' => $selectedCatalogId];
            if ('search_offerings' == $currentRoute) {
                $currentRouteParameters = $currentRequest->get('_route_params');
                if (!empty($currentRouteParameters['catalogId']) && $selectedCatalogId->isEqual($this->osidIdMap->fromString($currentRouteParameters['catalogId']))) {
                    $routeParameters = array_merge($routeParameters, $currentRequest->query->all());
                }
            }
            $menu[$catalog->getDisplayName()]->addChild(
                'Search',
                [
                    'route' => 'search_offerings',
                    'routeParameters' => $routeParameters,
                ],
            );

            // Add any manually configured links for this catalog.
            $catalogIdString = $this->osidIdMap->toString($selectedCatalogId);
            if (!empty($this->catalogMenuLinks[$catalogIdString])) {
                foreach ($this->catalogMenuLinks[$catalogIdString] as $link) {
                    $menu[$catalog->getDisplayName()]->addChild($link['label'], ['uri' => $link['uri']]);
                    $menu[$catalog->getDisplayName()][$link['label']]->setLinkAttribute('class', 'link-external');
                }
            }
        }

        // Schedule link.
        $user = $this->security->getUser();
        $menu->addChild('Schedule Builder', ['route' => 'schedules']);
        if ($user) {
            $menu->addChild('Schedule Builder', ['route' => 'schedules']);
        } else {
            $menu->addChild('Schedule Builder', [
                'route' => 'login',
                'routeParameters' => [
                    'returnTo' => $this->urlGenerator->generate('schedules', ['catalogId' => $selectedCatalogId]),
                ],
            ]);
        }

        return $menu;
    }
}
