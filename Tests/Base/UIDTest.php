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

/**
 * Class UIDTest.
 */
class UIDTest extends AsyncKernelFunctionalTest
{
    /**
     * Test uid.
     */
    public function testUID()
    {
        $this->assertNotEmpty(self::$kernel->getUID());
    }

    /**
     * Test empty uid.
     */
    public function testEmptyUID()
    {
        $kernel = $this->getKernel();
        $this->expectException(\Exception::class);
        $kernel->getUID();
    }

    /**
     * Test unique uid.
     */
    public function testUniqueUID()
    {
        $kernel1 = $this->getKernel();
        $kernel1->boot();
        $kernel2 = $this->getKernel();
        $kernel2->boot();
        $this->assertNotEmpty($kernel1->getUID());
        $this->assertNotEmpty($kernel2->getUID());
        $this->assertNotEquals($kernel1->getUID(), $kernel2->getUID());
    }
}
