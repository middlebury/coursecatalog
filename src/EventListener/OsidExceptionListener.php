<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class OsidExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        // Convert uncaught osid_NotFoundExceptions to NotFoundHttpExceptions.
        if ($e instanceof \osid_NotFoundException) {
            $event->setThrowable(new NotFoundHttpException($e->getMessage(), $e));
        }
        // Convert uncaught osid_InvalidArgumentExceptions to BadRequestHttpException.
        if ($e instanceof \osid_InvalidArgumentException) {
            $event->setThrowable(new BadRequestHttpException($e->getMessage(), $e));
        }
    }
}
