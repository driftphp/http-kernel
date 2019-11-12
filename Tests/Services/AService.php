<?php


namespace Drift\HttpKernel\Tests\Services;

use Drift\HttpKernel\AsyncEventDispatcherInterface;
use Drift\HttpKernel\TraceableAsyncEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AService
 */
final class AService
{
    public $equal = false;
    public $isTraceable = false;

    /**
     * AService constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param AsyncEventDispatcherInterface $dispatcher
     */
    public function __construct(
        EventDispatcherInterface $dispatcher1,
        AsyncEventDispatcherInterface $dispatcher2
    )
    {
        $this->equal = ($dispatcher1 === $dispatcher2);
        $this->isTraceable = ($dispatcher2 instanceof TraceableAsyncEventDispatcher);
    }
}