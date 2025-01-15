<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    /**
     * Add any other dependency you need...
     */
    public function __construct(
        private FactoryInterface $factory,
    ) {
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('All Catalogs', ['route' => 'catalogs']);

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
}
