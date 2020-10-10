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

namespace Drift\HttpKernel\Tests;

use Drift\HttpKernel\Event\DomainEventEnvelope;
use Drift\HttpKernel\Event\PreloadEvent;
use Drift\HttpKernel\Event\ShutdownEvent;
use Drift\HttpKernel\Tests\Event\Event1;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;

/**
 * Class Listener.
 */
class Listener
{
    /**
     * Handle get Response.
     *
     * @param RequestEvent $event
     *
     * @return PromiseInterface
     */
    public function handleGetResponsePromiseA(RequestEvent $event)
    {
        return (resolve())
            ->then(function () use ($event) {
                $event->setResponse(new Response('A'));
            });
    }

    /**
     * Handle get Response.
     *
     * @param RequestEvent $event
     *
     * @return PromiseInterface
     */
    public function handleGetResponsePromiseB(RequestEvent $event)
    {
        return (resolve())
            ->then(function () use ($event) {
                $event->setResponse(new Response('B'));
            });
    }

    /**
     * Handle get Response.
     *
     * @param RequestEvent $event
     */
    public function handleGetResponsePromiseNothing(RequestEvent $event)
    {
    }

    /**
     * Handle get Exception.
     *
     * @param ExceptionEvent $event
     */
    public function handleGetExceptionNothing(ExceptionEvent $event)
    {
    }

    /**
     * Handle get Exception.
     *
     * @param ExceptionEvent $event
     *
     * @return PromiseInterface
     */
    public function handleGetExceptionA(ExceptionEvent $event)
    {
        return (resolve())
            ->then(function () use ($event) {
                $event->setResponse(new Response('EXC', 404));
            });
    }

    /**
     * Handle get Response 1.
     *
     * @param RequestEvent $event
     *
     * @return PromiseInterface
     */
    public function handleGetResponsePromise1(RequestEvent $event): PromiseInterface
    {
        return
            (resolve())
                ->then(function () {
                    $_GET['partial'] .= '1';
                });
    }

    /**
     * Handle get Response 1.
     *
     * @param RequestEvent $event
     */
    public function handleGetResponsePromise2(RequestEvent $event)
    {
        $_GET['partial'] .= '2';
    }

    /**
     * Handle get Response 1.
     *
     * @param RequestEvent $event
     */
    public function handleGetResponsePromise3(RequestEvent $event)
    {
        $_GET['partial'] .= '3';
    }

    /**
     * Handle view.
     *
     * @param ViewEvent $event
     */
    public function handleView(ViewEvent $event)
    {
        return (resolve($event))
            ->then(function (ViewEvent $event) {
                $event->setResponse(new JsonResponse($event->getControllerResult()));
            });
    }

    /**
     * Handle response.
     *
     * @param ResponseEvent $event
     */
    public function handleResponseEvent(ResponseEvent $event)
    {
        return (resolve($event))
            ->then(function (ResponseEvent $event) {
                if ($event->getRequest()->query->has('replace_response')) {
                    $event->setResponse(new Response('response_replaced'));
                }
            });
    }

    /**
     * Handle preload.
     *
     * @param PreloadEvent $event
     */
    public function handlePreload(PreloadEvent $event)
    {
        $_GET['preloaded'] = true;
    }

    /**
     * Handle shutdown.
     *
     * @param ShutdownEvent $event
     */
    public function handleShutdown(ShutdownEvent $event)
    {
        $_GET['shutdown'] = true;
    }

    /**
     * Handle event1.
     *
     * @param Event1 $event1
     */
    public function handleEvent1(Event1 $event1)
    {
        $_GET['event1'] = true;
    }

    /**
     * Handle event2.
     *
     * @param DomainEventEnvelope $event2
     */
    public function handleEvent2(DomainEventEnvelope $event2)
    {
        $_GET['event2'] = true;
    }
}
