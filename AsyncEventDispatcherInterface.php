<?php


namespace Drift\HttpKernel;


use React\Promise\PromiseInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent as Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Interface AsyncEventDispatcherInterface
 */
interface AsyncEventDispatcherInterface extends EventDispatcherInterface
{
    /**
     * Dispatch an event asynchronously.
     *
     * @param string $eventName
     * @param Event $event
     *
     * @return PromiseInterface
     */
    public function asyncDispatch(
        string $eventName,
        Event $event
    );

    /**
     * Triggers the listeners of an event.
     *
     * This method can be overridden to add functionality that is executed
     * for each listener.
     *
     * @param callable[] $listeners
     * @param string $eventName
     * @param Event $event
     *
     * @return PromiseInterface
     */
    public function doAsyncDispatch(
        array $listeners,
        string $eventName,
        Event $event
    );
}