<?php


namespace Symfony\Component\HttpKernel;


use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

/**
 * Interface AsyncEventDispatcherInterface
 */
interface AsyncEventDispatcherInterface extends EventDispatcherInterface
{
    /**
     * Dispatch an event asynchronously.
     *
     * @param string $eventName
     * @param KernelEvent $event
     *
     * @return PromiseInterface
     */
    public function asyncDispatch(
        string $eventName,
        KernelEvent $event
    );

    /**
     * Triggers the listeners of an event.
     *
     * This method can be overridden to add functionality that is executed
     * for each listener.
     *
     * @param callable[] $listeners
     * @param string $eventName
     * @param KernelEvent $event
     *
     * @return PromiseInterface
     */
    public function doAsyncDispatch(
        array $listeners,
        string $eventName,
        KernelEvent $event
    );
}