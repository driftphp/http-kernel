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

namespace Drift\HttpKernel;

use Drift\HttpKernel\Exception\AsyncHttpKernelNeededException;
use React\EventLoop\LoopInterface;
use React\Filesystem\Filesystem;
use React\Promise\PromiseInterface;
use React\Promise\RejectedPromise;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AsyncKernel.
 */
abstract class AsyncKernel extends Kernel implements CompilerPassInterface
{
    /**
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     */
    public function handleAsync(Request $request): PromiseInterface
    {
        $httpKernel = $this->getHttpKernel();
        if (!$httpKernel instanceof AsyncHttpKernel) {
            return new RejectedPromise(
                new AsyncHttpKernelNeededException('In order to use this AsyncKernel, you need to have the HttpAsyncKernel installed')
            );
        }

        return $httpKernel->handleAsync($request);
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('event_dispatcher')) {
            $this->isDebug()
                ? $this->processEventDispatcherDebug($container)
                : $this->processEventDispatcher($container);
        }

        if ($container->has('http_kernel')) {
            $container
                ->getDefinition('http_kernel')
                ->setClass(AsyncHttpKernel::class);
        }

        if (!$container->has('reactphp.event_loop')) {
            $loop = new Definition(LoopInterface::class);
            $loop->setSynthetic(true);
            $loop->setPublic(true);
            $container->setDefinition('reactphp.event_loop', $loop);
        }

        if ($container->has('reactphp.event_loop')) {
            $container->setAlias(LoopInterface::class, 'reactphp.event_loop');
            $container->setAlias('event_loop', 'reactphp.event_loop');
            $container->setAlias('drift.event_loop', 'reactphp.event_loop');
        }

        /*
         * Create a filesystem instance
         */
        if (!$container->has('drift.filesystem')) {
            $filesystem = new Definition(Filesystem::class, [
                new Reference('drift.event_loop'),
            ]);

            $filesystem->setFactory([
                Filesystem::class,
                'create',
            ]);

            $filesystem->setPublic(true);
            $container->setDefinition('drift.filesystem', $filesystem);
            $container->setAlias(Filesystem::class, 'drift.filesystem');
        }
    }

    /**
     * Process event dispatcher.
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
