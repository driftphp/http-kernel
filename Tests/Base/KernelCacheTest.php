<?php


namespace Drift\HttpKernel\Tests\Base;

use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;

/**
 * Class KernelCacheTest
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

    public function testWithCache()
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