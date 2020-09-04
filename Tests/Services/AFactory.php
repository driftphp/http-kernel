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

namespace Drift\HttpKernel\Tests\Services;

use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;

/**
 * Class AService.
 */
final class AFactory
{
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * AFactory constructor.
     *
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * Create a class.
     */
    public static function createAFulfilledClass(): PromiseInterface
    {
        return (resolve())
            ->then(function () {
                return new AClass();
            });
    }

    /**
     * Create a class.
     */
    public static function createARejectedClass(): PromiseInterface
    {
        return (resolve())
            ->then(function () {
                throw new \Exception();
            });
    }
}
