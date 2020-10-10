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

final class AsyncKernelEvents
{
    /**
     * The PRELOAD event occurs once the kernel is properly booted and before
     * the first request is handled.
     *
     * This event allows you to load services in the dependency injection, to
     * preload clients, and so on.
     *
     * @Event("Drift\HttpKernel\Event\PreloadEvent")
     */
    const PRELOAD = 'kernel.preload';

    /**
     * The SHUTDOWN event occurs once the kernel is asked to be closed
     * gracefully.
     *
     * This event allows you to flush elements from memory, close connections
     * and so on.
     *
     * @Event("Drift\HttpKernel\Event\ShutdownEvent")
     */
    const SHUTDOWN = 'kernel.shutdown';
}
