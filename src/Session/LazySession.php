<?php

namespace App\Session;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

/**
 * Don't consider the session started if it is empty.
 *
 * When determining if autoCacheControl should be used to set Responses as
 * private, the Symfony\Component\HttpKernel\EventListener\AbstractSessionListener
 * will look at getUsageIndex() if the session is an instance of
 * Symfony\Component\HttpFoundation\Session\Session and isStarted() otherwise.
 * Implementing this class as a wrapper rather than an extension of Session
 * avoids the call to getUsageIndex() which gets incremented for reads.
 */
class LazySession implements FlashBagAwareSessionInterface, \IteratorAggregate, \Countable
{
    public function __construct(
        private Session $wrappedSession,
    ) {
    }

    /**
     * Starts the session storage.
     *
     * @throws \RuntimeException if session fails to start
     */
    public function start(): bool
    {
        $this->wrappedSession->start();
    }

    /**
     * Returns the session ID.
     */
    public function getId(): string
    {
        return $this->wrappedSession->getId();
    }

    /**
     * Sets the session ID.
     *
     * @return void
     */
    public function setId(string $id)
    {
        $this->wrappedSession->setId($id);
    }

    /**
     * Returns the session name.
     */
    public function getName(): string
    {
        return $this->wrappedSession->getName();
    }

    /**
     * Sets the session name.
     *
     * @return void
     */
    public function setName(string $name)
    {
        $this->wrappedSession->setName();
    }

    /**
     * Invalidates the current session.
     *
     * Clears all session attributes and flashes and regenerates the
     * session and deletes the old session from persistence.
     *
     * @param int|null $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                           will leave the system settings unchanged, 0 sets the cookie
     *                           to expire with browser session. Time is in seconds, and is
     *                           not a Unix timestamp.
     */
    public function invalidate(?int $lifetime = null): bool
    {
        return $this->wrappedSession->invalidate($lifetime);
    }

    /**
     * Migrates the current session to a new session id while maintaining all
     * session attributes.
     *
     * @param bool     $destroy  Whether to delete the old session or leave it to garbage collection
     * @param int|null $lifetime Sets the cookie lifetime for the session cookie. A null value
     *                           will leave the system settings unchanged, 0 sets the cookie
     *                           to expire with browser session. Time is in seconds, and is
     *                           not a Unix timestamp.
     */
    public function migrate(bool $destroy = false, ?int $lifetime = null): bool
    {
        return $this->wrappedSession->migrate($destroy, $lifetime);
    }

    /**
     * Force the session to be saved and closed.
     *
     * This method is generally not required for real sessions as
     * the session will be automatically saved at the end of
     * code execution.
     *
     * @return void
     */
    public function save()
    {
        // Only save if the session actually has data.
        if (!$this->wrappedSession->isEmpty()) {
            $this->wrappedSession->save();
        }
    }

    /**
     * Checks if an attribute is defined.
     */
    public function has(string $name): bool
    {
        return $this->wrappedSession->has($name);
    }

    /**
     * Returns an attribute.
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->wrappedSession->get($name, $default);
    }

    /**
     * Sets an attribute.
     *
     * @return void
     */
    public function set(string $name, mixed $value)
    {
        // throw new \Exception("Setting session value $name => $value");
        $this->wrappedSession->set($name, $value);
    }

    /**
     * Returns attributes.
     */
    public function all(): array
    {
        return $this->wrappedSession->all();
    }

    /**
     * Sets attributes.
     *
     * @return void
     */
    public function replace(array $attributes)
    {
        $this->wrappedSession->replace($attributes);
    }

    /**
     * Removes an attribute.
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove(string $name): mixed
    {
        return $this->wrappedSession->remove($name);
    }

    /**
     * Clears all attributes.
     *
     * @return void
     */
    public function clear()
    {
        $this->wrappedSession->clear();
    }

    /**
     * Checks if the session was started.
     */
    public function isStarted(): bool
    {
        // Don't consider an empty session started.
        return $this->wrappedSession->isStarted() && !$this->wrappedSession->isEmpty();
    }

    /**
     * Registers a SessionBagInterface with the session.
     *
     * @return void
     */
    public function registerBag(SessionBagInterface $bag)
    {
        $this->wrappedSession->registerBag($bag);
    }

    /**
     * Gets a bag instance by name.
     */
    public function getBag(string $name): SessionBagInterface
    {
        return $this->wrappedSession->getBag($name);
    }

    /**
     * Gets session meta.
     */
    public function getMetadataBag(): MetadataBag
    {
        return $this->wrappedSession->getMetadataBag();
    }

    public function getFlashBag(): FlashBagInterface
    {
        return $this->wrappedSession->getFlashBag();
    }

    /**
     * Returns an iterator for attributes.
     *
     * @return \ArrayIterator<string, mixed>
     */
    public function getIterator(): \ArrayIterator
    {
        return $this->wrappedSession->getIterator();
    }

    public function count(): int
    {
        return $this->wrappedSession->count();
    }
}
