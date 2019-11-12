<?php


namespace Drift\HttpKernel\Tests;

use Drift\HttpKernel\Tests\Services\AService;

/**
 * Class AutowiringTest
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
            ['resource' => dirname(__FILE__) . '/autowiring.yml']
        ];

        return $configuration;
    }

    /**
     * Test autowiring
     */
    public function testAutowiring()
    {
        $aService = $this->get(AService::class);
        $this->assertTrue($aService->equal);
        $this->assertFalse($aService->isTraceable);
    }
}