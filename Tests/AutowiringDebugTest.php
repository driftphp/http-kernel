<?php


namespace Drift\HttpKernel\Tests;

use Drift\HttpKernel\Tests\Services\AService;

/**
 * Class AutowiringDebugTest
 */
class AutowiringDebugTest extends AsyncKernelFunctionalTest
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
        $this->assertTrue($aService->isTraceable);
    }

    /**
     * Kernel in debug mode
     *
     * @return bool
     */
    protected static function debug(): bool
    {
        return true;
    }
}