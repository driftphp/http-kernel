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

namespace Drift\HttpKernel;

use function Clue\React\Block\await;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;

/**
 * Class AsyncContainer.
 */
class AsyncServiceAwaiter
{
    /**
     * Await.
     *
     * @param LoopInterface $loop
     * @param object        $service
     *
     * @return object
     */
    public static function await(
        LoopInterface $loop,
        $service
    ) {
        if ($service instanceof PromiseInterface) {
            $service = await($service, $loop);
            $loop->run();
        }

        return $service;
    }
}
