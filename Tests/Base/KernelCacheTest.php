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
 * Class KernelCacheTest.
 */
class KernelCacheTest extends AsyncKernelFunctionalTest
{
    public function testWithoutCache()
    {
        $_ENV['DRIFT_CACHE_ENABLED'] = '0';
        static::setUpBeforeClass();
        $cacheDir = self::$kernel->getCacheDir();
        $firstKernelCacheCreationTime = lstat($cacheDir)['ctime'];
        sleep(1);

        static::setUpBeforeClass();
        $cacheDir = self::$kernel->getCacheDir();
        $secondKernelCacheCreationTime = lstat($cacheDir)['ctime'];

        $this->assertGreaterThan($firstKernelCacheCreationTime, $secondKernelCacheCreationTime);
        unset($_ENV['DRIFT_CACHE_ENABLED']);
    }

    public function testWithCacheFromStaticSetting()
    {
        static::$kernel = static::getKernel();
        static::$kernel->forceCacheUsage();
        static::$kernel->boot();

        $cacheDir = self::$kernel->getCacheDir();
        $firstKernelCacheCreationTime = lstat($cacheDir)['ctime'];
        sleep(1);

        static::$kernel = static::getKernel();
        static::$kernel->forceCacheUsage();
        $cacheDir = self::$kernel->getCacheDir();
        $secondKernelCacheCreationTime = lstat($cacheDir)['ctime'];

        $this->assertEquals($firstKernelCacheCreationTime, $secondKernelCacheCreationTime);
    }

    public function testWithCacheWithEnv()
    {
        $_ENV['DRIFT_CACHE_ENABLED'] = '1';
        static::setUpBeforeClass();
        $cacheDir = self::$kernel->getCacheDir();
        $firstKernelCacheCreationTime = lstat($cacheDir)['ctime'];
        sleep(1);

        static::setUpBeforeClass();
        $cacheDir = self::$kernel->getCacheDir();
        $secondKernelCacheCreationTime = lstat($cacheDir)['ctime'];

        $this->assertEquals($firstKernelCacheCreationTime, $secondKernelCacheCreationTime);
        unset($_ENV['DRIFT_CACHE_ENABLED']);
    }
}
