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

/**
 * Class KernelServicesTest.
 */
class KernelServicesTest extends AsyncKernelFunctionalTest
{
    /**
     * Test autowiring.
     */
    public function testServices()
    {
        $this->assertInstanceof(LoopInterface::class, $this->get('reactphp.event_loop'));
        $this->assertInstanceof(Filesystem::class, $this->get('drift.filesystem'));
    }
}
