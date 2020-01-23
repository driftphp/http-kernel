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

namespace Drift\HttpKernel\Tests\Base;

use Clue\React\Block;
use Drift\HttpKernel\RequestWithContext;
use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use React\EventLoop\StreamSelectLoop;
use React\Promise;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContextTest.
 */
class ContextTest extends AsyncKernelFunctionalTest
{
    /**
     * Test context.
     */
    public function testDifferentContexts()
    {
        $loop = new StreamSelectLoop();
        $promise1 = self::$kernel
            ->handleAsync(new RequestWithContext([], [], [], [], [], [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/context',
                'SERVER_PORT' => 80,
            ]))
            ->then(function (Response $response) {
                return $response->getContent();
            });

        $promise2 = self::$kernel
            ->handleAsync(new RequestWithContext([], [], [], [], [], [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/context',
                'SERVER_PORT' => 80,
            ]))
            ->then(function (Response $response) {
                return $response->getContent();
            });

        $loop->run();
        list($value1, $value2) = Block\await(
            Promise\all([
                $promise1,
                $promise2,
            ]), $loop
        );

        $this->assertNotEquals($value1, $value2);
    }
}
