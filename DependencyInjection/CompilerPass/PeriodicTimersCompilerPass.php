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
use Symfony\Component\DependencyInjection\Parameter;
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
                $interval = $this->getParameterIfFormattedOrDefault($serviceRow['interval']);
                $method = $this->getParameterIfFormattedOrDefault($serviceRow['method']);

                $periodicTimer->addMethodCall('addServiceCall', [
                    $interval,
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

    /**
     * Get as parameter reference if has format or default otherwise.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function getParameterIfFormattedOrDefault($value)
    {
        if (!\is_string($value)) {
            return $value;
        }

        return $this->hasParameterFormat($value)
            ? new Parameter(trim($value, '%'))
            : $value;
    }

    /**
     * Has parameter format.
     *
     * @param string $string
     *
     * @return bool
     */
    private function hasParameterFormat(string $string): bool
    {
        $len = strlen($string);

        return
            ($len - 1 === strlen(rtrim($string, '%'))) &&
            ($len - 1 === strlen(ltrim($string, '%')))
        ;
    }
}
