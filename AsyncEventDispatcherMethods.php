<?php


namespace Drift\HttpKernel;

use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Trait AsyncEventDispatcherMethods
 */
trait AsyncEventDispatcherMethods
{
    /**
     * Dispatch an event asynchronously.
     *
     * @param string      $eventName
     * @param Event $event
     *
     * @return PromiseInterface
     */
    public function asyncDispatch(
        string $eventName,
        Event $event
    ) {
        if ($listeners = $this->getListeners($eventName)) {
            return $this->doAsyncDispatch($listeners, $eventName, $event);
        }

        return new FulfilledPromise($event);
    }

    /**
     * Triggers the listeners of an event.
     *
     * This method can be overridden to add functionality that is executed
     * for each listener.
     *
     * @param callable[]  $listeners
     * @param string      $eventName
     * @param Event $event
     *
     * @return PromiseInterface
     */
    public function doAsyncDispatch(
        array $listeners,
        string $eventName,
        Event $event
    ) {
        $promise = new FulfilledPromise();
        foreach ($listeners as $listener) {
            $promise = $promise->then(function () use ($event, $eventName, $listener) {
                return
                    (new FulfilledPromise())
                        ->then(function () use ($event, $eventName, $listener) {
                            return $event->isPropagationStopped()
                                ? new FulfilledPromise()
                                : $listener($event, $eventName, $this);
                        });
            });
        }

        return $promise->then(function () use ($event) {
            return $event;
        });
    }
}