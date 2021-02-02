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

namespace Drift\HttpKernel\Tests\Base;

use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use React\EventLoop\LoopInterface;
use React\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class KernelServicesTest.
 */
class KernelServicesTest extends AsyncKernelFunctionalTest
{
    /**
     * @group without-filesystem
     */
    public function testServicesWithoutFilesystem()
    {
        $this->assertInstanceof(LoopInterface::class, $this->get('reactphp.event_loop'));
        $this->expectException(ServiceNotFoundException::class);
        $this->get('drift.filesystem');
    }

    /**
     * @group with-filesystem
     */
    public function testServicesWithFilesystem()
    {
        $this->assertInstanceof(LoopInterface::class, $this->get('reactphp.event_loop'));
        $this->assertInstanceof(Filesystem::class, $this->get('drift.filesystem'));
    }
}
