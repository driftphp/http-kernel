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
use Drift\HttpKernel\AsyncKernelEvents;
use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use Drift\HttpKernel\Tests\Listener;
use React\EventLoop\StreamSelectLoop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PreloadTest.
 */
class PreloadTest extends AsyncKernelFunctionalTest
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
                    'event' => AsyncKernelEvents::PRELOAD,
                    'method' => 'handlePreload',
                ],
            ],
        ];

        return $configuration;
    }

    /**
     * Everything should work as before in the world of sync requests.
     */
    public function testPreload()
    {
        $loop = new StreamSelectLoop();

        self::$kernel->preload();
        $promise = self::$kernel
            ->handleAsync(new Request([], [], [], [], [], [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/get',
                'SERVER_PORT' => 80,
            ]))
            ->then(function (Response $response) {
                $content = json_decode($response->getContent(), true);
                $this->assertTrue($content['preloaded']);
            });

        $loop->run();
        Block\await($promise, $loop);
    }
}
