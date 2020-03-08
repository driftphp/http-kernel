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

use Exception;
use function React\Promise\reject;
use function React\Promise\resolve;
use React\Promise\PromiseInterface;
use RingCentral\Psr7\Response as Psr7Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        return resolve(new Response('Y'));
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
        return reject(new Exception('E2'));
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
}
