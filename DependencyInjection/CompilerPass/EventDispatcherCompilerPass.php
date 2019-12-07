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

namespace Drift\HttpKernel\DependencyInjection\CompilerPass;

use Drift\HttpKernel\AsyncEventDispatcher;
use Drift\HttpKernel\AsyncEventDispatcherInterface;
use Drift\HttpKernel\TraceableAsyncEventDispatcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EventDispatcherCompilerPass
 */
class EventDispatcherCompilerPass implements CompilerPassInterface
{
    /**
     * @var bool
     *
     * Is debug
     */
    private $isDebug;

    /**
     * EventDispatcherCompilerPass constructor.
     *
     * @param bool $isDebug
     */
    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('event_dispatcher')) {
            $this->isDebug
                ? $this->processEventDispatcherDebug($container)
                : $this->processEventDispatcher($container);
        }
    }

    /**
     * Process event dispatcher.
     *
     * @param ContainerBuilder $container
     */
    private function processEventDispatcher(ContainerBuilder $container)
    {
        $container
            ->getDefinition('event_dispatcher')
            ->setClass(AsyncEventDispatcher::class);

        $container->setAlias(AsyncEventDispatcherInterface::class, 'event_dispatcher');
    }

    /**
     * Process event dispatcher in debug.
     *
     * @param ContainerBuilder $container
     */
    private function processEventDispatcherDebug(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('debug.event_dispatcher')) {
            $this->processEventDispatcher($container);

            return;
        }

        $container
            ->getDefinition('debug.event_dispatcher')
            ->setClass(TraceableAsyncEventDispatcher::class);

        $container->setAlias(AsyncEventDispatcherInterface::class, 'event_dispatcher');
    }
}