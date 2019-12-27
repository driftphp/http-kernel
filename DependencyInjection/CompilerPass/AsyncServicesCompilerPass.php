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

namespace Drift\HttpKernel\DependencyInjection\CompilerPass;

use Drift\HttpKernel\AsyncServiceAwaiter;
use React\EventLoop\LoopInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AsyncServicesCompilerPass.
 */
class AsyncServicesCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $servicesId = $container->findTaggedServiceIds('await');

        foreach ($servicesId as $serviceId => $_) {
            $promiseDefinition = $container->getDefinition($serviceId);
            $container->setDefinition($serviceId.'.promise', $promiseDefinition);

            $decorator = new Definition($promiseDefinition->getClass(), [
                new Reference(LoopInterface::class),
                new Reference($serviceId.'.promise'),
            ]);

            $decorator->setPublic($promiseDefinition->isPublic());
            $decorator->setFactory([
                AsyncServiceAwaiter::class,
                'await',
            ]);

            $container->setDefinition($serviceId, $decorator);
        }
    }
}
