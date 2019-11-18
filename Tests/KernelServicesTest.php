<?php


namespace Drift\HttpKernel\Tests;

use React\EventLoop\LoopInterface;
use React\Filesystem\Filesystem;

/**
 * Class KernelServicesTest
 */
class KernelServicesTest extends AsyncKernelFunctionalTest
{
    /**
     * Test autowiring
     */
    public function testServices()
    {
        $this->assertInstanceof(LoopInterface::class, $this->get('reactphp.event_loop'));
        $this->assertInstanceof(Filesystem::class, $this->get('drift.filesystem'));
    }
}