<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Ensure that responses aren't marked private when the session is empty.
 *
 * AbstractSessionListener sets responses as Cache-Control: private if there is
 * a firewall that *allows* authenticated users, even if there is no
 * authenticated user for the current request and that request is anonymous.
 *
 * This listener ensures that reads to the session (such as from the standard
 * firewall configuration) do not make responses to anonymous requests
 * uncacheable.
 *
 * This class is based on the work of Tuğrul Topuz in:
 * - https://github.com/symfony/symfony/issues/37113#issuecomment-643341100
 * - https://github.com/tugrul/slcc-poc/blob/17f59f4207f80d5ff5f7bcc62ca554ba7b36d909/src/EventSubscriber/SessionCacheControlSubscriber.php
 */
class EmptySessionCacheControlListener
{
    #[AsEventListener(event: KernelEvents::RESPONSE, priority: -999)]
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!defined(AbstractSessionListener::class.'::NO_AUTO_CACHE_CONTROL_HEADER')) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->hasSession()) {
            return;
        }

        $session = $request->getSession();

        // The existence of the isEmpty() function is not guarantee because it
        // isn't in the SessionInterface contract.
        if (!($session instanceof Session) || !method_exists($session, 'isEmpty')) {
            $fields = $session->all();

            foreach ($fields as &$field) {
                if (!empty($field)) {
                    return;
                }
            }
        } elseif (!$session->isEmpty()) {
            return;
        }

        $event->getResponse()->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, true);
    }
}
