<?php

namespace App\Session;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface;

class LazySessionFactory implements SessionFactoryInterface
{
    private RequestStack $requestStack;
    private SessionStorageFactoryInterface $storageFactory;
    private ?\Closure $usageReporter;

    public function __construct(RequestStack $requestStack, SessionStorageFactoryInterface $storageFactory, ?callable $usageReporter = null)
    {
        $this->requestStack = $requestStack;
        $this->storageFactory = $storageFactory;
        $this->usageReporter = null === $usageReporter ? null : $usageReporter(...);
    }

    public function createSession(): SessionInterface
    {
        return new LazySession(new Session($this->storageFactory->createStorage($this->requestStack->getMainRequest()), null, null, $this->usageReporter));
    }
}
