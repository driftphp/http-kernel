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

namespace Drift\HttpKernel\Tests;

use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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
        return (new FulfilledPromise())
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
        return (new FulfilledPromise())
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
        return (new FulfilledPromise())
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
            (new FulfilledPromise())
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
        $event->setResponse(new JsonResponse($event->getControllerResult()));
    }
}
