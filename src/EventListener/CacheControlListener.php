<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Set Cache-Control headers to public for anonymous requests.
 */
final class CacheControlListener
{
    public const NEVER_CACHE_HEADER = 'X-App-Never-Cache-Response';

    public function __construct(
        private int $maxAge = 300,
        private int $sharedMaxAge = 604800,
    ) {
    }

    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($event->isMainRequest()) {
            // Symfony firewalls seem to initialize the session even when there
            // is no data in the session. Ensure that we actually have session
            // data before marking the response as private.
            if ($event->getRequest()->getSession()->isStarted()) {
                $event->getResponse()->setPrivate();
            } elseif ($event->getResponse()->headers->get(self::NEVER_CACHE_HEADER)) {
                // Ensure that a response isn't public if our custom header has been
                // added to it.
                $event->getResponse()->setPrivate();
                $event->getResponse()->headers->remove(self::NEVER_CACHE_HEADER);
            } else {
                $response = $event->getResponse();
                $response->setPublic();
                $response->setMaxAge($this->maxAge);
                $response->setSharedMaxAge($this->sharedMaxAge);
            }
        }
    }
}
