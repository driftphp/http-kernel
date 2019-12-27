<?php


namespace Drift\HttpKernel;

use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use function Clue\React\Block\await;

/**
 * Class AsyncContainer
 */
class AsyncServiceAwaiter
{
    /**
     * Await
     *
     * @param LoopInterface $loop
     * @param object $service
     *
     * @return object
     */
    public static function await(
        LoopInterface $loop,
        $service
    )
    {
        return ($service instanceof PromiseInterface)
            ? await($service, $loop)
            : $service;
    }
}