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
use Drift\HttpKernel\AsyncEventDispatcherInterface;
use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use Drift\HttpKernel\Tests\Event\Event1;
use Drift\HttpKernel\Tests\Listener;
use React\EventLoop\Factory;

/**
 * Class AsyncEventDispatcherTest.
 */
class AsyncEventDispatcherTest extends AsyncKernelFunctionalTest
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
        $configuration['services']['dispatcher_test'] = [
            'alias' => AsyncEventDispatcherInterface::class,
        ];

        $configuration['services']['listener'] = [
            'class' => Listener::class,
            'tags' => [
                [
                    'name' => 'kernel.event_listener',
                    'event' => Event1::class,
                    'method' => 'handleEvent1',
                ],
            ],
        ];

        return $configuration;
    }

    /**
     * Test async event dispatcher.
     */
    public function testAsyncDispatch()
    {
        $loop = Factory::create();

        $_GET['event1'] = false;
        $promise = self::get('dispatcher_test')
            ->asyncDispatch(new Event1())
            ->then(function (Event1 $_) {
                $this->assertTrue($_GET['event1']);
            });

        $loop->run();
        Block\await($promise, $loop);
    }
}
