<?php


namespace Drift\HttpKernel\Tests\Base;

use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use Drift\HttpKernel\Tests\Services\AClass;
use Drift\HttpKernel\Tests\Services\AFactory;

/**
 * Class ContainerRejectedPromiseTest
 */
class ContainerRejectedPromiseTest extends AsyncKernelFunctionalTest
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
        $configuration['services'][AClass::class] = [
            'factory' => [
                AFactory::class,
                'createARejectedClass'
            ],
            'tags' => [
                ['name' => 'await']
            ]
        ];

        return $configuration;
    }

    /**
     * Test a class instance
     */
    public function testAClass()
    {
        $this->expectException(\Exception::class);
        $this->get(AClass::class);
    }
}