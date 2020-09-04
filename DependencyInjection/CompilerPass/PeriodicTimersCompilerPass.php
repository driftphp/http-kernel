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

use Drift\HttpKernel\PeriodicTimer\PeriodicTimer;
use React\EventLoop\LoopInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PeriodicTimersCompilerPass.
 */
class PeriodicTimersCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $servicesId = $container->findTaggedServiceIds('periodic_timer');

        $periodicTimer = new Definition(PeriodicTimer::class, [
            new Reference(LoopInterface::class),
        ]);

        foreach ($servicesId as $serviceId => $serviceRows) {
            foreach ($serviceRows as $serviceRow) {
                $frequency = $serviceRow['interval'];

                if ($frequency <= 0) {
                    throw new \Exception('You should define an interval value (in seconds) when defining a periodic timer');
                }

                $method = $serviceRow['method'];
                $periodicTimer->addMethodCall('addServiceCall', [
                    $frequency,
                    new Reference($serviceId),
                    $method,
                ]);
            }
        }

        $periodicTimer->addTag('kernel.event_listener', [
            'event' => 'kernel.preload',
        ]);

        $container->setDefinition(PeriodicTimer::class, $periodicTimer);
    }
}
