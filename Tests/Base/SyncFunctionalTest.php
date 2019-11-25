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

namespace Drift\HttpKernel\Tests\Base;

use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SyncFunctionalTest.
 */
class SyncFunctionalTest extends AsyncKernelFunctionalTest
{
    /**
     * Everything should work as before in the world of sync requests.
     */
    public function testSyncKernel()
    {
        $request = new Request([], [], [], [], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/value',
            'SERVER_PORT' => 80,
        ]);

        $this->assertEquals(
            'X',
            self::$kernel->handle($request)->getContent()
        );

        $request = new Request([], [], [], [], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/exception',
            'SERVER_PORT' => 80,
        ]);

        try {
            $this->assertEquals(
                'X',
                self::$kernel->handle($request)->getContent()
            );
            $this->fail('Exception expected to be thrown');
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }
    }
}
