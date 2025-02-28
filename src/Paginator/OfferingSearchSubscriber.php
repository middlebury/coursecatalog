<?php

// file: src/Subscriber/PaginateDirectorySubscriber.php
// requires Symfony\Component\Finder\Finder

namespace App\Paginator;

use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OfferingSearchSubscriber implements EventSubscriberInterface
{
    public function items(ItemsEvent $event): void
    {
        if (!is_object($event->target) || !($event->target instanceof CourseOfferingSearchAdaptor)) {
            return;
        }

        $event->items = $event->target->getItems($event->getOffset(), $event->getLimit());
        $event->count = $event->target->count();
        $event->stopPropagation();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'knp_pager.items' => ['items', 1/* increased priority to override any internal */],
        ];
    }
}
