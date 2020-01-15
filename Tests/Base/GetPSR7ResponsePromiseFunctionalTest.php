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
use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use React\EventLoop\StreamSelectLoop;
use RingCentral\Psr7\Response as Psr7Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetPSR7ResponsePromiseFunctionalTest.
 */
class GetPSR7ResponsePromiseFunctionalTest extends AsyncKernelFunctionalTest
{
    /**
     * Everything should work as before in the world of sync requests.
     *
     * @group lele
     */
    public function testSyncKernel()
    {
        $loop = new StreamSelectLoop();

        $promise = self::$kernel
            ->handleAsync(new Request([], [], [], [], [], [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/psr7',
                'SERVER_PORT' => 80,
            ]))
            ->then(function (Psr7Response $response) {
                $this->assertEquals(
                    'psr7',
                    $response->getBody()->getContents()
                );
            });

        $loop->run();
        Block\await($promise, $loop);
    }
}
