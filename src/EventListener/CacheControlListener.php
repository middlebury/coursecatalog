<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Set Cache-Control headers to public for anonymous requests.
 *
 * Note that this relies on App\Session\LazySession to report
 * isStarted() => false when the session is empty.
 */
final class CacheControlListener
{
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
            } else {
                $response = $event->getResponse();
                $response->setPublic();
                $response->setMaxAge($this->maxAge);
                $response->setSharedMaxAge($this->sharedMaxAge);
            }
        }
    }
}
