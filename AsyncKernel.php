<?php

/*
 * This file is part of the Symfony Async Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Symfony\Component\HttpKernel;

use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use React\Promise\RejectedPromise;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AsyncHttpKernelNeededException;

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
     *
     * @param Request $request
     *
     * @return PromiseInterface
     */
    public function handleAsync(Request $request): PromiseInterface {
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
        };

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
        }
    }

    /**
     * Process event dispatcher
     *
     * @param ContainerBuilder $container
     */
    private function processEventDispatcher(ContainerBuilder $container) {
        $container
            ->getDefinition('event_dispatcher')
            ->setClass(AsyncEventDispatcher::class);

        $container->setAlias(AsyncEventDispatcherInterface::class, 'event_dispatcher');
    }

    /**
     * Process event dispatcher in debug
     *
     * @param ContainerBuilder $container
     */
    private function processEventDispatcherDebug(ContainerBuilder $container) {
        $container
            ->getDefinition('debug.event_dispatcher')
            ->setClass(TraceableAsyncEventDispatcher::class);

        $container->setAlias(AsyncEventDispatcherInterface::class, 'event_dispatcher');
    }
}
