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

use Drift\HttpKernel\Context;
use Drift\HttpKernel\RequestWithContext;
use Exception;
use React\Promise\FulfilledPromise;
use React\Promise\PromiseInterface;
use React\Promise\RejectedPromise;
use RingCentral\Psr7\Response as Psr7Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller.
 */
class Controller
{
    /**
     * Return value.
     *
     * @return Response
     */
    public function getValue(): Response
    {
        return new Response('X');
    }

    /**
     * Return fulfilled promise.
     *
     * @return PromiseInterface
     */
    public function getPromise(): PromiseInterface
    {
        return new FulfilledPromise(new Response('Y'));
    }

    /**
     * Throw exception.
     *
     * @throws Exception
     */
    public function throwException()
    {
        throw new Exception('E1');
    }

    /**
     * Return rejected promise.
     *
     * @return PromiseInterface
     */
    public function getPromiseException(): PromiseInterface
    {
        return new RejectedPromise(new Exception('E2'));
    }

    /**
     * Return array.
     *
     * @return array
     */
    public function getSimpleResult(): array
    {
        return ['a', 'b'];
    }

    /**
     * Return json response.
     *
     * @return JsonResponse
     */
    public function getGet(): JsonResponse
    {
        return new JsonResponse($_GET);
    }

    /**
     * Return react response.
     *
     * @return Psr7Response
     */
    public function getPSR7Response(): Psr7Response
    {
        return new Psr7Response(200, [], 'psr7');
    }

    /**
     * Return context response.
     *
     * @param RequestWithContext $request
     *
     * @return JsonResponse
     */
    public function getContext(RequestWithContext $request): JsonResponse
    {
        $context = $request->getContext();
        $request->getContext()->set(uniqid(), 'x');

        return new JsonResponse($context->all());
    }
}
