<?php

/*
 * This file is part of the Drift Http Kernel
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TwigTest.
 */
class TwigTest extends AsyncKernelFunctionalTest
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
                    'event' => 'kernel.view',
                    'method' => 'handleView',
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
        $request = new Request([], [], [], [], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/simple-result',
            'SERVER_PORT' => 80,
        ]);

        $_GET['partial'] = '';
        $promise = self::$kernel
            ->handleAsync($request)
            ->then(function (Response $response) {
                $this->assertEquals(
                    json_encode(['a', 'b']),
                    $response->getContent()
                );
            });

        $loop->run();
        Block\await($promise, $loop);
    }
}
