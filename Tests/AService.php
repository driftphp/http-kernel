<?php


namespace Symfony\Component\HttpKernel\Tests;

use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\AsyncEventDispatcherInterface;

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
        $this->isTraceable = ($dispatcher2 instanceof TraceableEventDispatcherInterface);
    }
}