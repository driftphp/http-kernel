<?php

/*
 * This file is part of the Drift Http Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\HttpKernel\Tests\NoStopwatch;

use Drift\HttpKernel\Tests\Base\AutowiringDebugTest as BaseTest;
use Drift\HttpKernel\Tests\Services\AService;

/**
 * Class AutowiringDebugTest.
 */
class AutowiringDebugTest extends BaseTest
{
    /**
     * Kernel in debug mode.
     *
     * @return string
     */
    protected static function environment(): string
    {
        return 'devnostopwatch';
    }

    /**
     * Test autowiring.
     */
    public function testAutowiring()
    {
        $aService = $this->get(AService::class);
        $this->assertTrue($aService->equal);
        $this->assertTrue($aService->isTraceable);
    }
}
