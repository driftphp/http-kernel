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

namespace Drift\HttpKernel\PeriodicTimer;

use React\EventLoop\LoopInterface;

/**
 * Class PeriodicTimer
 */
final class PeriodicTimer
{
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * @param float  $frequencyInSeconds
     * @param Object $service
     * @param string $method
     */
    public function addServiceCall(
        float $frequencyInSeconds,
        Object $service,
        string $method
    )
    {
        $this
            ->loop
            ->addPeriodicTimer($frequencyInSeconds, function() use ($service, $method) {
                $service->$method();
            });
    }
}