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

namespace Drift\HttpKernel\Tests\WithStopwatch;

use Drift\HttpKernel\Tests\Base\AsyncDebugFunctionalTest as BaseTest;

/**
 * Class AsyncDebugFunctionalTest.
 */
class AsyncDebugFunctionalTest extends BaseTest
{
    /**
     * Kernel in debug mode.
     *
     * @return string
     */
    protected static function environment(): string
    {
        return 'devwithstopwatch';
    }
}
