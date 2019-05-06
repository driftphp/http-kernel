<?php

/*
 * This file is part of the Symfony Async Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Symfony\Component\HttpKernel;

use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\PromiseEvent;

/**
 * Class AsyncEventDispatcher.
 */
class AsyncEventDispatcher extends EventDispatcher
{
    /**
     * Dispatch an event asynchronously.
     *
     * @param string       $eventName
     * @param PromiseEvent $event
     *
     * @return PromiseInterface
     */
    public function asyncDispatch(
        string $eventName,
        PromiseEvent $event
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
     * @param callable[]   $listeners
     * @param string       $eventName
     * @param PromiseEvent $event
     *
     * @return PromiseInterface
     */
    protected function doAsyncDispatch(
        array $listeners,
        string $eventName,
        PromiseEvent $event
    ) {
        $promise = new FulfilledPromise();
        foreach ($listeners as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }

            $result = $listener($event, $eventName, $this);
            if (!$result instanceof PromiseInterface) {
                $result = new FulfilledPromise($result);
            }

            $promise = $promise->then(function () use ($result) {
                return new FulfilledPromise(function (PromiseEvent $event) use ($result) {
                    return $event->hasPromise()
                        ? new FulfilledPromise()
                        : $result;
                });
            });
        }

        return $promise->then(function () use ($event) {
            return $event;
        });
    }
}
