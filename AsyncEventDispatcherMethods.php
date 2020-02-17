<?php

/*
 * This file is part of the DriftPHP Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\HttpKernel;

use Drift\HttpKernel\Event\DomainEventEnvelope;
use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Trait AsyncEventDispatcherMethods.
 */
trait AsyncEventDispatcherMethods
{
    /**
     * Dispatch an event asynchronously.
     *
     * @param object $event
     * @param string $eventName
     *
     * @return PromiseInterface
     */
    public function asyncDispatch(
        $event,
        string $eventName = null
    ) {
        $eventName = $eventName ?? \get_class($event);
        $dispatchableEvent = $event instanceof Event
            ? $event
            : new DomainEventEnvelope($event);

        if ($listeners = $this->getListeners($eventName)) {
            return $this
                ->doAsyncDispatch($listeners, $eventName, $dispatchableEvent)
                ->then(function () use ($event) {
                    return $event;
                });
        }

        return new FulfilledPromise($event);
    }

    /**
     * Triggers the listeners of an event.
     *
     * This method can be overridden to add functionality that is executed
     * for each listener.
     *
     * @param callable[] $listeners
     * @param string     $eventName
     * @param Event      $event
     *
     * @return PromiseInterface
     */
    private function doAsyncDispatch(
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

        return $promise;
    }
}
