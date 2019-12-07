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

use React\EventLoop\LoopInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class EventLoopCompilerPass
 */
class EventLoopCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
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
    }
}