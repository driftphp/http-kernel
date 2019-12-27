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
use Drift\HttpKernel\Tests\Listener;
use React\EventLoop\StreamSelectLoop;
use React\Promise;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AsyncFunctionalTest.
 */
class AsyncFunctionalTest extends AsyncKernelFunctionalTest
{
    /**
     * Decorate configuration.
     *
     * @param array $configuration
     *
     * @return array
     */
    protected static function decorateConfiguration(array $configuration): array
    {
        $configuration = parent::decorateConfiguration($configuration);
        $configuration['services']['listener'] = [
            'class' => Listener::class,
            'tags' => [
                [
                    'name' => 'kernel.event_listener',
                    'event' => 'kernel.request',
                    'method' => 'handleGetResponsePromiseNothing',
                ],
                [
                    'name' => 'kernel.event_listener',
                    'event' => 'kernel.exception',
                    'method' => 'handleGetExceptionNothing',
                ],
            ],
        ];

        return $configuration;
    }

    /**
     * Everything should work as before in the world of sync requests.
     */
    public function testSyncKernel()
    {
        $loop = new StreamSelectLoop();

        $promise1 = self::$kernel
            ->handleAsync(new Request([], [], [], [], [], [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/promise',
                'SERVER_PORT' => 80,
            ]))
            ->then(function (Response $response) {
                $this->assertEquals(
                    'Y',
                    $response->getContent()
                );
            });

        $promise2 = self::$kernel
            ->handleAsync(new Request([], [], [], [], [], [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/promise-exception',
                'SERVER_PORT' => 80,
            ]))
            ->then(null, function (\Throwable $exception) {
                $this->assertEquals(
                    'E2',
                    $exception->getMessage()
                );
            });

        $loop->run();
        Block\await(
            Promise\all([
                $promise1,
                $promise2,
            ]), $loop
        );
    }
}
