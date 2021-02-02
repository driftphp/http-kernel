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

use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Interface AsyncEventDispatcherInterface.
 */
interface AsyncEventDispatcherInterface extends EventDispatcherInterface
{
    /**
     * @param object      $event
     * @param string|null $eventName
     *
     * @return PromiseInterface
     */
    public function asyncDispatch(
        $event,
        string $eventName = null
    ): PromiseInterface;
}
