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

namespace Drift\HttpKernel\Tests\Services;

use Drift\HttpKernel\AsyncEventDispatcherInterface;
use Drift\HttpKernel\TraceableAsyncEventDispatcher;
use React\EventLoop\LoopInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AService.
 */
final class AService
{
    public bool $equal = false;
    public bool $isTraceable = false;

    /**
     * AService constructor.
     *
     * @param EventDispatcherInterface      $dispatcher
     * @param AsyncEventDispatcherInterface $dispatcher
     * @param LoopInterface                 $loop
     */
    public function __construct(
        EventDispatcherInterface $dispatcher1,
        AsyncEventDispatcherInterface $dispatcher2,
        LoopInterface $loop
    ) {
        $this->equal = ($dispatcher1 === $dispatcher2);
        $this->isTraceable = ($dispatcher2 instanceof TraceableAsyncEventDispatcher);
    }
}
