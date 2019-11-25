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
use Drift\HttpKernel\Tests\Services\AService;

/**
 * Class AutowiringTest.
 */
class AutowiringTest extends AsyncKernelFunctionalTest
{
    /**
     * Decorate configuration.
     *
     * @param array $configuration
     *
     * @return array
     */
    protected static function decorateConfiguration(array $configuration): array
    {
        $configuration = parent::decorateConfiguration($configuration);
        $configuration['imports'] = [
            ['resource' => dirname(__FILE__).'/../autowiring.yml'],
        ];

        return $configuration;
    }

    /**
     * Test autowiring.
     */
    public function testAutowiring()
    {
        $aService = $this->get(AService::class);
        $this->assertTrue($aService->equal);
        $this->assertFalse($aService->isTraceable);
    }
}
