<?php


namespace Drift\HttpKernel\Tests\Base;


use Drift\HttpKernel\Tests\AsyncKernelFunctionalTest;
use Drift\HttpKernel\Tests\Services\ACounter;
use function Clue\React\Block\await;
use function Drift\React\usleep;

/**
 * Class PeriodicTimerTest
 */
class PeriodicTimerTest extends AsyncKernelFunctionalTest
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
        $configuration['services'][ACounter::class] = [
            'class' => ACounter::class,
            'tags' => [
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.1',
                    'method' => 'increaseI',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.1',
                    'method' => 'increaseX',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.3',
                    'method' => 'increase2X',
                ],
                [
                    'name' => 'periodic_timer',
                    'interval' => '0.7',
                    'method' => 'increaseX',
                ]
            ],
        ];

        return $configuration;
    }

    /**
     * Test periodic timer
     */
    public function testPeriodicTimer()
    {
        self::$kernel->preload();

        $loop = static::get('reactphp.event_loop');
        await(usleep(1050000, $loop), $loop);

        $this->assertEquals(10, self::get(ACounter::class)->getI());
        $this->assertEquals(14, self::get(ACounter::class)->getX());

        await(usleep(350000, $loop), $loop);

        $this->assertEquals(13, self::get(ACounter::class)->getI());
        $this->assertEquals(19, self::get(ACounter::class)->getX());
    }
}